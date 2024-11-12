<?php

namespace App\Http\Controllers;

use App\Models\Documentos;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentosController extends Controller
{
    public function index()
    {
        $documentos = Documentos::with('client')->paginate(30);
        return view('documentos.index', compact('documentos'));
    }

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
        $cliente = Client::find($request->client_id);

        if (!$cliente) {
            return 0;
        }


        $validatedData = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'identificacion' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'pasaporte' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'permiso_de_trabajo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'hoja_marron' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'pruebas' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'historia' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'residencia_permanente' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'caq' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'extras' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
        $documento = new Documentos();
        $documento->client_id = $cliente->id;
        $documento->historia = $validatedData['historia'] ?? null;



        $fileFields = ['identificacion', 'pasaporte', 'permiso_de_trabajo', 'hoja_marron', 'pruebas', 'residencia_permanente', 'caq', 'extras'];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('documentos', $filename, 'public');
                $documento->$field = $path;
            }
        }

        $documento->save();

        return redirect()->route('client.show', $documento->client_id)->with('success', 'Documentos subidos correctamente');
        // Documento::create($validatedData);

        // return redirect()->route('documentos.index')->with('success', 'Documento creado correctamente');
    }

    public function show(Documentos $documento)
    {
        return view('documentos.show', compact('documento'));
    }

    public function edit(Documentos $documento)
    {
        $clients = Client::all();
        return view('documentos.edit', compact('documento', 'clients'));
    }

    public function update(Request $request, Documentos $documento)
    {
        $validatedData = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'historia' => 'nullable|string',
            'identificacion' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'pasaporte' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'permiso_de_trabajo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'hoja_marron' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'pruebas' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'residencia_permanente' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'caq' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'extras' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $documento->client_id = $validatedData['client_id'];
        $documento->historia = $validatedData['historia'] ?? $documento->historia;

        $fileFields = ['identificacion', 'pasaporte', 'permiso_de_trabajo', 'hoja_marron', 'pruebas', 'residencia_permanente', 'caq', 'extras'];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                // Eliminar el archivo anterior si existe
                if ($documento->$field) {
                    Storage::disk('public')->delete($documento->$field);
                }

                $file = $request->file($field);
                $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('documentos', $filename, 'public');
                $documento->$field = $path;
            }
        }

        $documento->save();

        return redirect()->route('client.show', $documento->client_id)->with('success', 'Documentos actualizados correctamente');
    }

    public function destroy(Documentos $documento)
    {
        $fileFields = ['identificacion', 'pasaporte', 'permiso_de_trabajo', 'hoja_marron', 'pruebas', 'residencia_permanente', 'caq', 'extras'];

        foreach ($fileFields as $field) {
            if ($documento->$field) {
                Storage::disk('public')->delete($documento->$field);
            }
        }

        $documento->delete();

        return redirect()->route('documentos.index')->with('success', 'Documento eliminado correctamente');
    }
}