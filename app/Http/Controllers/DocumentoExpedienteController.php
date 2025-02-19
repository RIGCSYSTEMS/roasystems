<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Client;
use App\Models\Expediente;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\File;
use Illuminate\Routing\Controller;
use App\Models\DocumentoExpediente;
use App\Models\TipoDocumentoExpediente;

class DocumentoExpedienteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($expedienteId)
    {
        $expediente = Expediente::findOrFail($expedienteId);
        return view('expedientes.index', compact('expedientes'));
    }
    
    public function getDocumentos($expedienteId)
    {
        $documentosexp = DocumentoExpediente::where('expediente_id', $expedienteId)
        // ->with('tipoDocumentoExpediente')    
        ->get()
            ->map(function ($documentoexp) {
                return [
                    'id' => $documentoexp->id,
                    'nombre' => $documentoexp->nombre,
                    'tipo_documento_expediente' => $documentoexp->tipoDocumentoExpediente->nombre,
                    'formato' => $documentoexp->formato,
                    'estado' => $documentoexp->estado,
                    'observaciones' => $documentoexp->observaciones,
                    'created_at' => $documentoexp->created_at,
                    'archivo_url' => $documentoexp->ruta ? asset('storage/' . $documentoexp->ruta) : null
                ];
            });
        return response()->json($documentosexp);
    }
    
    public function store(Request $request)
    {
        $expedienteId = $request->input('expediente_id') ?? $request->input('expedientes_id');
    
    if (!$expedienteId) {
        return response()->json([
            'success' => false,
            'mensaje' => 'No se proporcionó el ID del expediente'
        ], 400);
    }

    $formData = [
        'expedientes_id' => $expedienteId,
        'nombre' => $request->nombre,
        // 'formato' => $request->formato,
        'observaciones' => $request->observaciones
    ];
        try {
            $validated = $request->validate([
                'nombre' => 'required|string',
                // 'formato' => 'required|in:PDF,IMAGEN',
                'archivo' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'observaciones' => 'nullable|string',
                'expediente_id' => 'required|exists:expedientes,id'
            ]);
    
            $expedienteId = $request->expediente_id;
            $file = $request->file('archivo');

            // Detectar automáticamente el tipo de archivo
            $mimeType = $file->getMimeType();
            $formato = $this->detectarFormato($mimeType);

            if (!$formato) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'Tipo de archivo no soportado'
                ], 400);
            }

            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $extension;
    
            $path = $file->storeAs(
                'expedientes/' . $expedienteId,
                $fileName,
                'local'
            );
    
            $documentosexp = new DocumentoExpediente();
            $documentosexp->nombre = $request->nombre;
            $documentosexp->expediente_id = $expedienteId;
            $documentosexp->tipo_documento_expediente_id = $request->tipo_documento_expediente_id;
            $documentosexp->user_id = Auth::id();
            $documentosexp->formato = $formato;
            $documentosexp->estado = 'pendiente';
            $documentosexp->ruta = $path;
            $documentosexp->observaciones = $request->observaciones;
            $documentosexp->save();
    
            return response()->json([
                'success' => true,
                'mensaje' => 'Documento guardado correctamente',
                'documento' => $documentosexp
            ], 201);
    
        } catch (\Exception $e) {
            Log::error('Error al procesar documento: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'mensaje' => 'Error al procesar el documento: ' . $e->getMessage()
            ], 500);
        }
    }

    private function detectarFormato($mimeType)
    {
        $pdfMimes = ['application/pdf'];
        $imageMimes = ['image/jpeg', 'image/png', 'image/jpg'];

        if (in_array($mimeType, $pdfMimes)) {
            return 'PDF';
        } elseif (in_array($mimeType, $imageMimes)) {
            return 'IMAGEN';
        }

        return null;
    }
    
    public function update(Request $request, $id)
    {
        $documentosexp = DocumentoExpediente::findOrFail($id);
        $user = Auth::user();

        if ($documentosexp->estado === 'Aceptado' && !in_array($user->role, ['ADMIN', 'DIRECTOR', 'ABOGADO'])) {
            return response()->json([
                'success' => false,
                'message' => 'No tiene permiso para modificar documentos aceptados'
            ], 403);
        }

        if ($user->role === 'CLIENTE' && $documentosexp->estado === 'Aceptado') {
            return response()->json([
                'success' => false,
                'message' => 'No puede modificar documentos aceptados'
            ], 403);
        }

        $request->validate([
            'nombre' => 'nullable|string',
            // 'formato' => 'required|in:PDF,IMAGEN',
            'archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'observaciones' => 'nullable|string'
        ]);

        try {
            if ($request->hasFile('archivo')) {
                if ($documentosexp->ruta) {
                    Storage::disk('local')->delete($documentosexp->ruta);
                }
                
                $path = $request->file('archivo')->store('expedientes/' . $documentosexp->expedientes_id, 'local');
                $documentosexp->ruta = $path;
            }

            $documentosexp->nombre = $request->nombre;
            $documentosexp->tipo_documento_expediente_id = $request->tipo_documento_expediente_id;
            // $documentosexp->formato = $request->formato;
            $documentosexp->observaciones = $request->observaciones;
            $documentosexp->save();

            return response()->json([
                'success' => true,
                'documento' => $documentosexp
            ]);

        } catch (\Exception $e) {
            Log::error('Error al actualizar documento: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el documento'
            ], 500);
        }
    }
    
    public function destroy($id)
    {
        try {
            $documentosexp = DocumentoExpediente::findOrFail($id);
            $user = Auth::user();

            if ($documentosexp->estado === 'Aceptado' && !in_array($user->role, ['ADMIN', 'DIRECTOR', 'ABOGADO'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tiene permiso para eliminar documentos aceptados'
                ], 403);
            }
            
            if ($documentosexp->ruta) {
                Storage::disk('local')->delete($documentosexp->ruta);
            }
            
            $documentosexp->delete();
            return response()->json(['success' => true], 204);

        } catch (\Exception $e) {
            Log::error('Error al eliminar documento: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el documento'
            ], 500);
        }
    }

    public function actualizarEstado(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        $user = Auth::user();
        if (!$user || !in_array($user->role, ['ADMIN', 'DIRECTOR', 'ABOGADO'])) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $documentosexp = DocumentoExpediente::findOrFail($id);
        $documentosexp->estado = $request->estado;
        $documentosexp->save();

        return response()->json(['success' => true]);
    }

    public function getTiposDocumentoExpediente()
    {
        $tipos = TipoDocumentoExpediente::all();
        return response()->json($tipos);
    }

    public function validarDocumento($id)
    {
        try {
            $documentosexp = DocumentoExpediente::findOrFail($id);
            $documentosexp->estado = 'Aceptado';
            $documentosexp->save();
            return response()->json([
                'success' => true,
                'documento' => $documentosexp
            ]);
        } catch (\Exception $e) {
            Log::error('Error al validar documento: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al validar el documento'
            ], 500);
        }
    }

    public function descargar($id)
    {
        try {
            $documentosexp = DocumentoExpediente::findOrFail($id);
            
            if (!Gate::allows('ver-documento', $documentosexp)) {
                abort(403, 'No tiene permiso para ver este documento');
            }

            if (!Storage::disk('local')->exists($documentosexp->ruta)) {
                abort(404, 'Archivo no encontrado');
            }

            $nombreArchivo = basename($documentosexp->ruta);
            $filePath = Storage::disk('local')->path($documentosexp->ruta);
            return response()->download($filePath, $nombreArchivo);

        } catch (\Exception $e) {
            Log::error('Error al descargar documento: ' . $e->getMessage());
            abort(500, 'Error al descargar el documento');
        }
    }

    public function visualizar($id)
    {
        try {
            $documentosexp = DocumentoExpediente::findOrFail($id);
            
            if (!Gate::allows('ver-documento', $documentosexp)) {
                abort(403, 'No tiene permiso para ver este documento');
            }

            if (!Storage::disk('local')->exists($documentosexp->ruta)) {
                abort(404, 'Archivo no encontrado');
            }

            $file = Storage::disk('local')->get($documentosexp->ruta);
            $type = File::mimeType(Storage::disk('local')->path($documentosexp->ruta));

            return response($file, 200)
                ->header('Content-Type', $type)
                ->header('Content-Disposition', 'inline; filename="' . basename($documentosexp->ruta) . '"');

        } catch (\Exception $e) {
            Log::error('Error al visualizar documento: ' . $e->getMessage());
            abort(500, 'Error al visualizar el documento');
        }
    }
}