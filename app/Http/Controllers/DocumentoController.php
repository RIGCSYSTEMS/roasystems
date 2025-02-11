<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Client;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\File;
use Illuminate\Routing\Controller;

class DocumentoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($clientId)
    {
        $client = Client::findOrFail($clientId);
        $tiposDocumento = TipoDocumento::all();
        return view('documentos.index', compact('client', 'tiposDocumento'));
    }
    
    public function getDocumentos($clientId)
    {
        $documentos = Documento::with('tipoDocumento')
            ->where('client_id', $clientId)
            ->get()
            ->map(function ($documento) {
                return [
                    'id' => $documento->id,
                    'nombre' => $documento->tipoDocumento->nombre,
                    'tipo_documento_id' => $documento->tipo_documento_id,
                    'formato' => $documento->formato,
                    'estado' => $documento->estado,
                    'observaciones' => $documento->observaciones,
                    'created_at' => $documento->created_at,
                    'archivo_url' => $documento->ruta ? asset('storage/' . $documento->ruta) : null
                ];
            });
        return response()->json($documentos);
    }
    
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'tipo_documento_id' => 'required|exists:tipos_documentos,id',
                'formato' => 'required|in:PDF,IMAGEN',
                'archivo' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'observaciones' => 'nullable|string',
                'client_id' => 'required|exists:clients,id'
            ]);
    
            $path = $request->file('archivo')->store(
                'documentos/' . $request->client_id,
                'local'
            );
    
            $documento = new Documento();
            $documento->tipo_documento_id = $request->tipo_documento_id;
            $documento->client_id = $request->client_id;
            $documento->user_id = Auth::id();
            $documento->formato = $request->formato;
            $documento->estado = 'pendiente';
            $documento->ruta = $path;
            $documento->observaciones = $request->observaciones;
            $documento->save();
    
            return response()->json([
                'success' => true,
                'mensaje' => 'Documento guardado correctamente',
                'documento' => $documento
            ], 201);
    
        } catch (\Exception $e) {
            Log::error('Error al procesar documento: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'mensaje' => 'Error al procesar el documento: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function update(Request $request, $id)
    {
        $documento = Documento::findOrFail($id);
        $user = Auth::user();

        if ($documento->estado === 'Aceptado' && !in_array($user->role, ['ADMIN', 'DIRECTOR', 'ABOGADO'])) {
            return response()->json([
                'success' => false,
                'message' => 'No tiene permiso para modificar documentos aceptados'
            ], 403);
        }

        if ($user->role === 'CLIENTE' && $documento->estado === 'Aceptado') {
            return response()->json([
                'success' => false,
                'message' => 'No puede modificar documentos aceptados'
            ], 403);
        }

        $request->validate([
            'tipo_documento_id' => 'required|exists:tipos_documentos,id',
            'formato' => 'required|in:PDF,IMAGEN',
            'archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'observaciones' => 'nullable|string'
        ]);

        try {
            if ($request->hasFile('archivo')) {
                if ($documento->ruta) {
                    Storage::disk('local')->delete($documento->ruta);
                }
                
                $path = $request->file('archivo')->store('documentos/' . $documento->client_id, 'local');
                $documento->ruta = $path;
            }

            $documento->tipo_documento_id = $request->tipo_documento_id;
            $documento->formato = $request->formato;
            $documento->observaciones = $request->observaciones;
            $documento->save();

            return response()->json([
                'success' => true,
                'documento' => $documento
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
            $documento = Documento::findOrFail($id);
            $user = Auth::user();

            if ($documento->estado === 'Aceptado' && !in_array($user->role, ['ADMIN', 'DIRECTOR', 'ABOGADO'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tiene permiso para eliminar documentos aceptados'
                ], 403);
            }
            
            if ($documento->ruta) {
                Storage::disk('local')->delete($documento->ruta);
            }
            
            $documento->delete();
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

        $documento = Documento::findOrFail($id);
        $documento->estado = $request->estado;
        $documento->save();

        return response()->json(['success' => true]);
    }

    public function getTiposDocumento()
    {
        $tipos = TipoDocumento::all();
        return response()->json($tipos);
    }

    public function validarDocumento($id)
    {
        try {
            $documento = Documento::findOrFail($id);
            $documento->estado = 'Aceptado';
            $documento->save();
            return response()->json([
                'success' => true,
                'documento' => $documento
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
            $documento = Documento::findOrFail($id);
            
            if (!Gate::allows('ver-documento', $documento)) {
                abort(403, 'No tiene permiso para ver este documento');
            }

            if (!Storage::disk('local')->exists($documento->ruta)) {
                abort(404, 'Archivo no encontrado');
            }

            $nombreArchivo = basename($documento->ruta);
            $filePath = Storage::disk('local')->path($documento->ruta);
            return response()->download($filePath, $nombreArchivo);

        } catch (\Exception $e) {
            Log::error('Error al descargar documento: ' . $e->getMessage());
            abort(500, 'Error al descargar el documento');
        }
    }

    public function visualizar($id)
    {
        try {
            $documento = Documento::findOrFail($id);
            
            if (!Gate::allows('ver-documento', $documento)) {
                abort(403, 'No tiene permiso para ver este documento');
            }

            if (!Storage::disk('local')->exists($documento->ruta)) {
                abort(404, 'Archivo no encontrado');
            }

            $file = Storage::disk('local')->get($documento->ruta);
            $type = File::mimeType(Storage::disk('local')->path($documento->ruta));

            return response($file, 200)
                ->header('Content-Type', $type)
                ->header('Content-Disposition', 'inline; filename="' . basename($documento->ruta) . '"');

        } catch (\Exception $e) {
            Log::error('Error al visualizar documento: ' . $e->getMessage());
            abort(500, 'Error al visualizar el documento');
        }
    }
}