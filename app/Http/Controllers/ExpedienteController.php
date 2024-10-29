<?php

namespace App\Http\Controllers;

use App\Models\Expediente;
use App\Models\Client;
use Illuminate\Http\Request;

class ExpedienteController extends Controller
{
    public function index()
    {
        $expedientes = Expediente::with('client')->paginate(30);
        return view('expedientes.index', compact('expedientes'));
    }

    public function create(Request $request)
    {
        $client_id = $request->query('client_id');
        $client = Client::findOrFail($client_id);
        return view('expedientes.create', compact('client'));
        // return view('expedientes.create');
    //     $clients = Client::all();
    // return view('expedientes.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'fecha_de_apertura' => 'required|date',
            'estatus_del_expediente' => 'required|string',
            'numero_de_dossier' => 'required|string',
            'despacho' => 'required|string',
            'abogado' => 'required|string',
            'honorarios' => 'required|numeric',
            'tipo' => 'required|string',
        ]);

        $expediente = Expediente::create($validatedData);

        return redirect()->route('client.show', $expediente->client_id)->with('success', 'Expediente creado correctamente');
    }

    public function show(Expediente $expediente)
    {
        return view('expedientes.show', compact('expediente'));
    }

    public function edit(Expediente $expediente)
    {
        return view('expedientes.edit', compact('expediente'));
    }

    public function update(Request $request, Expediente $expediente)
    {
        $validatedData = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'fecha_de_apertura' => 'required|date',
            'estatus_del_expediente' => 'required|string',
            'fecha_de_cierre' => 'nullable|date',
            'numero_de_dossier' => 'required|string',
            'despacho' => 'required|string',
            'abogado' => 'required|string',
            'honorarios' => 'required|numeric',
            'tipo' => 'required|string',
        ]);

        $expediente->update($validatedData);

        return redirect()->route('expedientes.index')->with('success', 'Expediente actualizado correctamente');
    }

    public function destroy(Expediente $expediente)
    {
        $expediente->delete();

        return redirect()->route('expedientes.index')->with('success', 'Expediente eliminado correctamente');
    }
}