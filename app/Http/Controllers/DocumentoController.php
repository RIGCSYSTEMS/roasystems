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
                    'archivo_url' => $documento->ruta ? Storage::url($documento->ruta) : null
                ];
            });
        return response()->json($documentos);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'tipo_documento_id' => 'required|exists:tipos_documentos,id',
            'formato' => 'required|in:PDF,IMAGEN',
            'archivo' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'observaciones' => 'nullable|string'
        ]);

        try {
            $path = $request->file('archivo')->store('documentos/' . $request->client_id, 'public');
            
            $documento = new Documento();
            $documento->tipo_documento_id = $request->tipo_documento_id;
            $documento->client_id = $request->client_id;
            $documento->user_id = Auth::id();
            $documento->formato = $request->formato;
            $documento->estado = 'Pendiente';
            $documento->ruta = $path;
            $documento->observaciones = $request->observaciones;
            $documento->save();

            return response()->json([
                'success' => true,
                'documento' => $documento
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error al guardar documento: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el documento'
            ], 500);
        }
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'tipo_documento_id' => 'required|exists:tipos_documentos,id',
            'formato' => 'required|in:PDF,IMAGEN',
            'archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'observaciones' => 'nullable|string'
        ]);

        try {
            $documento = Documento::findOrFail($id);

            if ($request->hasFile('archivo')) {
                // Eliminar archivo anterior si existe
                if ($documento->ruta) {
                    Storage::disk('public')->delete($documento->ruta);
                }
                
                $path = $request->file('archivo')->store('documentos/' . $documento->client_id, 'public');
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
            
            // Eliminar archivo si existe
            if ($documento->ruta) {
                Storage::disk('public')->delete($documento->ruta);
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

    public function getTiposDocumento()
    {
        $tipos = TipoDocumento::all();
        return response()->json($tipos);
    }
}