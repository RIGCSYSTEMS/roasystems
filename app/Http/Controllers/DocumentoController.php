<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use Illuminate\Http\Request;

class DocumentoController extends Controller
{
    public function index()
    {
        $documentos = Documento::with('client')->paginate(30);
        return view('documentos.index', compact('documentos'));
    }

    public function create()
    {
        return view('documentos.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'identificacion' => 'nullable|string',
            'pasaporte' => 'nullable|string',
            'permiso_de_trabajo' => 'nullable|string',
            'hoja_marron' => 'nullable|string',
            'pruebas' => 'nullable|string',
            'historia' => 'nullable|text',
            'residencia_permanente' => 'nullable|string',
            'caq' => 'nullable|string',
            'extras' => 'nullable|string',
        ]);

        Documento::create($validatedData);

        return redirect()->route('documentos.index')->with('success', 'Documento creado correctamente');
    }

    public function show(Documento $documento)
    {
        return view('documentos.show', compact('documento'));
    }

    public function edit(Documento $documento)
    {
        return view('documentos.edit', compact('documento'));
    }

    public function update(Request $request, Documento $documento)
    {
        $validatedData = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'identificacion' => 'nullable|string',
            'pasaporte' => 'nullable|string',
            'permiso_de_trabajo' => 'nullable|string',
            'hoja_marron' => 'nullable|string',
            'pruebas' => 'nullable|string',
            'historia' => 'nullable|text',
            'residencia_permanente' => 'nullable|string',
            'caq' => 'nullable|string',
            'extras' => 'nullable|string',
        ]);

        $documento->update($validatedData);

        return redirect()->route('documentos.index')->with('success', 'Documento actualizado correctamente');
    }

    public function destroy(Documento $documento)
    {
        $documento->delete();

        return redirect()->route('documentos.index')->with('success', 'Documento eliminado correctamente');
    }
}