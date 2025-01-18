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
                    return [
                        'id' => $documento->id,
                        'tipo_documento_id' => $documento->tipo_documento_id,
                        'formato' => $documento->formato,
                        'estado' => $documento->estado,
                        'created_at' => $documento->created_at,
                        'ruta' => asset($documento->ruta),
                        'observaciones' => $documento->observaciones
                    ];
                });

            return response()->json($documentos);
        } catch (\Exception $e) {
            Log::error('Error al cargar documentos:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error al cargar los documentos'], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $client_id = $request->query('client_id');
            $client = Client::findOrFail($client_id);
            $tiposDocumento = TipoDocumento::all();
            return view('documentos.create', compact('client', 'tiposDocumento'));
        } catch (\Exception $e) {
            Log::error('Error en create:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error al cargar el formulario de creación');
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
                $documento->ruta = 'storage/' . $path;
            }

            $documento->save();

            return redirect()->route('client.documentos', $request->client_id)
                           ->with('success', 'Documento subido correctamente');
        } catch (\Exception $e) {
            Log::error('Error en store:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error al guardar el documento');
        }
    }

    public function show($id)
    {
        try {
            $client = Client::findOrFail($id);
            $documentos = Documento::where('client_id', $id)
                ->with('tipoDocumento')
                ->get();
                
            return view('documentos.show', compact('client', 'documentos'));
        } catch (\Exception $e) {
            Log::error('Error en show:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error al mostrar los documentos del cliente');
        }
    }

    public function edit(Documento $documento)
    {
        try {
            $tiposDocumento = TipoDocumento::all();
            return view('documentos.edit', compact('documento', 'tiposDocumento'));
        } catch (\Exception $e) {
            Log::error('Error en edit:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error al cargar el formulario de edición');
        }
    }

    public function update(Request $request, Documento $documento)
    {
        try {
            $request->validate([
                'tipo_documento_id' => 'required|exists:tipos_documentos,id',
                'formato' => 'required|in:PDF,IMAGEN',
                'archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'observaciones' => 'nullable|string'
            ]);

            $documento->tipo_documento_id = $request->tipo_documento_id;
            $documento->formato = $request->formato;
            $documento->observaciones = $request->observaciones;

            if ($request->hasFile('archivo')) {
                if ($documento->ruta) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $documento->ruta));
                }

                $path = $request->file('archivo')->store('documentos/' . $documento->client_id, 'public');
                $documento->ruta = 'storage/' . $path;
            }

            $documento->save();

            return redirect()->route('client.documentos', $documento->client_id)
                           ->with('success', 'Documento actualizado correctamente');
        } catch (\Exception $e) {
            Log::error('Error en update:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error al actualizar el documento');
        }
    }

    public function destroy($id)
    {
        try {
            $documento = Documento::findOrFail($id);
            
            if ($documento->ruta) {
                Storage::disk('public')->delete(str_replace('storage/', '', $documento->ruta));
            }
            
            $documento->delete();
            return response()->json(['message' => 'Documento eliminado correctamente']);
        } catch (\Exception $e) {
            Log::error('Error al eliminar documento:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error al eliminar el documento'], 500);
        }
    }

    public function subirDocumento(Request $request, $clientId)
    {
        try {
            Log::info('Recibiendo solicitud de documento:', $request->all());
            
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
                $documento->ruta = 'storage/' . $path;
            }
    
            Log::info('Intentando guardar documento:', $documento->toArray());
            
            $documento->save();
    
            return response()->json([
                'message' => 'Documento guardado exitosamente',
                'documento' => [
                    'id' => $documento->id,
                    'tipo_documento_id' => $documento->tipo_documento_id,
                    'formato' => $documento->formato,
                    'estado' => $documento->estado,
                    'created_at' => $documento->created_at,
                    'ruta' => asset($documento->ruta),
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
            return response()->json([
                'message' => 'Error al guardar el documento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getTiposDocumento()
    {
        try {
            $tipos = TipoDocumento::all();
            Log::info('Tipos de documentos recuperados:', $tipos->toArray());
            return response()->json($tipos);
        } catch (\Exception $e) {
            Log::error('Error al obtener tipos de documentos:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error al obtener tipos de documentos'], 500);
        }
    }
}