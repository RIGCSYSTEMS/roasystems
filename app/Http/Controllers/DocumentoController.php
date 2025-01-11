<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    // public function index()
    // {
    //     $documentos = Documento::with('client')->paginate(30);
    //     return view('documentos.index', compact('documentos'));
    // }

    public function create(Request $request)
    {
        // return view('documentos.create');
        $client_id = $request->query('client_id');
        $client = Client::findOrFail($client_id);
        $clients = Client::all();
        return view('documentos.create', compact('client', 'clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'documentos' => 'required|array',
            'documentos.*.nombre_de_documento' => 'required|in:IDENTIFICACION,PASAPORTE,PERMISO DE TRABAJO,HOJA MARRON,PRUEBAS,HISTORIA,RESIDENCIA PERMANENTE,CAQ,EXTRAS',
            'documentos.*.tipo_de_documento' => 'required|in:PDF,IMAGEN',
            'documentos.*.archivo' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'documentos.*.observaciones' => 'nullable|string'
        ]);
    
        $client = Client::findOrFail($request->client_id);
    
        foreach ($request->documentos as $doc) {
            if (isset($doc['archivo'])) {
                $archivo = $doc['archivo'];
                $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                $path = $archivo->storeAs('public/documentos/' . $client->id, $nombreArchivo);
                $urlArchivo = 'storage/documentos/' . $client->id . '/' . $nombreArchivo;
    
                Documento::create([
                    'client_id' => $client->id,
                    'nombre_de_documento' => $doc['nombre_de_documento'],
                    'tipo_de_documento' => $doc['tipo_de_documento'],
                    'imagen_url' => $urlArchivo,
                    'observaciones' => $doc['observaciones'] ?? null
                ]);
            }
        }
    
        return redirect()->route('client.documentos', $client->id)->with('success', 'Documentos subidos correctamente');
    }

    public function show($id)
    {
        $client = Client::findOrFail($id);
        $documentos = Documento::where('client_id', $id)
            ->orderBy('nombre_de_documento')
            ->get();

        // Para almacenamiento local
        $documentos = $documentos->map(function ($documento) {
            if ($documento->imagen_url) {
                $documento->imagen_url = asset($documento->imagen_url);
            }
            return $documento;
        });

        return view('documentos.show', compact('client', 'documentos'));
    }

    public function edit(Documento $documento)
    {
        $clients = Client::all();
        return view('documentos.edit', compact('documento', 'clients'));
    }

    public function update(Request $request, Documento $documento)
    {
        $request->validate([
            'nombre_de_documento' => 'required|in:IDENTIFICACION,PASAPORTE,PERMISO DE TRABAJO,HOJA MARRON,PRUEBAS,HISTORIA,RESIDENCIA PERMANENTE,CAQ,EXTRAS',
            'tipo_de_documento' => 'required|in:PDF,IMAGEN',
            'archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'observaciones' => 'nullable|string'
        ]);

        $documento->nombre_de_documento = $request->nombre_de_documento;
        $documento->tipo_de_documento = $request->tipo_de_documento;
        $documento->observaciones = $request->observaciones;

        if ($request->hasFile('archivo')) {
            // Eliminar archivo antiguo
            if ($documento->imagen_url) {
                Storage::delete('public/' . str_replace('storage/', '', $documento->imagen_url));
            }

            // Subir nuevo archivo
            $archivo = $request->file('archivo');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            $path = $archivo->storeAs('public/documentos/' . $documento->client_id, $nombreArchivo);
            $documento->imagen_url = 'storage/documentos/' . $documento->client_id . '/' . $nombreArchivo;
        }

        $documento->save();

        return redirect()->route('client.documentos', $documento->client_id)->with('success', 'Documento actualizado correctamente');
    }

    public function destroy(Documento $documento)
    {
        $clientId = $documento->client_id;

        if ($documento->imagen_url) {
            Storage::delete('public/' . str_replace('storage/', '', $documento->imagen_url));
        }

        $documento->delete();

        return redirect()->route('client.documentos', $clientId)->with('success', 'Documento eliminado correctamente');
    }
    public function subirDocumento(Request $request, $clientId)
    {
        $request->validate([
            'nombre_de_documento' => 'required|in:IDENTIFICACION,PASAPORTE,PERMISO DE TRABAJO,HOJA MARRON,PRUEBAS,HISTORIA,RESIDENCIA PERMANENTE,CAQ,EXTRAS',
            'tipo_de_documento' => 'required|in:PDF,IMAGEN',
            'archivo' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240'
        ]);
    
        $client = Client::findOrFail($clientId);
        
        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            
            // Guardar el archivo en el directorio correcto
            $path = $archivo->storeAs('documentos/' . $clientId, $nombreArchivo, 'public');
            
            // Generar la URL correcta para el archivo
            $urlArchivo = 'storage/' . $path;
            
            Documento::create([
                'client_id' => $clientId,
                'nombre_de_documento' => $request->nombre_de_documento,
                'tipo_de_documento' => $request->tipo_de_documento,
                'imagen_url' => $urlArchivo
            ]);
    
            return redirect()->route('client.documentos', $clientId)->with('success', 'Documento subido correctamente');
        }
    
        return redirect()->route('client.documentos', $clientId)->with('error', 'Error al subir el documento');
    }
    public function view($id)
{
    $documento = Documento::findOrFail($id);
    return view('documentos.view', compact('documento'));
}
}