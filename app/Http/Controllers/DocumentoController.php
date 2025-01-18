<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Client;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DocumentoController extends Controller
{
    public function index($clientId)
    {
        try {
            $documentos = Documento::where('client_id', $clientId)
                ->with('tipoDocumento')
                ->get()
                ->map(function ($documento) {
                    // Log para depuración
                    Log::info('Ruta original en la base de datos:', ['ruta' => $documento->ruta]);
    
                    // Ajustar la ruta para eliminar duplicados
                    $ruta = str_replace('public/', '', $documento->ruta);
                    $ruta = Storage::url($ruta);
    
                    // Log para verificar el resultado final
                    Log::info('Ruta procesada:', ['ruta' => $ruta]);
    
                    return [
                        'id' => $documento->id,
                        'nombre_de_documento' => $documento->tipoDocumento->nombre ?? 'Sin tipo',
                        'tipo_documento_id' => $documento->tipo_documento_id,
                        'formato' => $documento->formato,
                        'estado' => $documento->estado,
                        'created_at' => $documento->created_at,
                        'ruta' => $ruta, // Ruta pública corregida
                        'observaciones' => $documento->observaciones
                    ];
                });
    
            return response()->json($documentos);
        } catch (\Exception $e) {
            Log::error('Error al cargar documentos:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error al cargar los documentos'], 500);
        }
    }
    public function show($clientId)
    {
        try {
            // Encuentra el cliente
            $client = Client::findOrFail($clientId);
    
            // Recupera los documentos asociados al cliente
            $documentos = Documento::where('client_id', $clientId)
                ->with('tipoDocumento') // Incluye el tipo de documento
                ->get()
                ->map(function ($documento) {
                    return [
                        'id' => $documento->id,
                        'nombre_de_documento' => $documento->tipoDocumento->nombre ?? 'Sin tipo',
                        'tipo_documento_id' => $documento->tipo_documento_id,
                        'formato' => $documento->formato,
                        'estado' => $documento->estado,
                        'created_at' => $documento->created_at,
                        'ruta' => Storage::url($documento->ruta), // Genera URL pública
                        'observaciones' => $documento->observaciones,
                    ];
                });
    
            // Devuelve la vista 'documentos.show' con los datos del cliente y los documentos
            return view('documentos.show', compact('client', 'documentos'));
        } catch (\Exception $e) {
            Log::error('Error en show:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error al mostrar los documentos del cliente');
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'client_id' => 'required|exists:clients,id',
                'tipo_documento_id' => 'required|exists:tipos_documentos,id',
                'formato' => 'required|in:PDF,IMAGEN',
                'archivo' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'observaciones' => 'nullable|string'
            ]);
    
            $documento = new Documento();
            $documento->client_id = $request->client_id;
            $documento->tipo_documento_id = $request->tipo_documento_id;
            $documento->user_id = Auth::id();
            $documento->formato = $request->formato;
            $documento->estado = 'Pendiente';
            $documento->observaciones = $request->observaciones;
    
            if ($request->hasFile('archivo')) {
                $path = $request->file('archivo')->store('documentos/' . $request->client_id, 'public');
                $documento->ruta = $path; // Guarda solo la ruta relativa
            }
    
            $documento->save();
    
            return redirect()->route('client.documentos', $request->client_id)
                             ->with('success', 'Documento subido correctamente');
        } catch (\Exception $e) {
            Log::error('Error en store:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error al guardar el documento');
        }
    }
    
    public function subirDocumento(Request $request, $clientId)
    {
        try {
            $request->validate([
                'tipo_documento_id' => 'required|exists:tipos_documentos,id',
                'formato' => 'required|in:PDF,IMAGEN',
                'archivo' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'observaciones' => 'nullable|string'
            ]);

            $documento = new Documento();
            $documento->client_id = $clientId;
            $documento->tipo_documento_id = $request->tipo_documento_id;
            $documento->user_id = Auth::id() ?? 1;
            $documento->formato = $request->formato;
            $documento->estado = 'Pendiente';
            $documento->observaciones = $request->observaciones;

            if ($request->hasFile('archivo')) {
                $path = $request->file('archivo')->store('documentos/' . $clientId, 'public');
                $documento->ruta = $path;
            }

            $documento->save();

            return response()->json([
                'message' => 'Documento guardado exitosamente',
                'documento' => [
                    'id' => $documento->id,
                    'tipo_documento_id' => $documento->tipo_documento_id,
                    'formato' => $documento->formato,
                    'estado' => $documento->estado,
                    'created_at' => $documento->created_at,
                    'ruta' => Storage::url($documento->ruta),
                    'observaciones' => $documento->observaciones
                ]
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación:', $e->errors());
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error al guardar documento:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error al guardar el documento'], 500);
        }
    }
}
