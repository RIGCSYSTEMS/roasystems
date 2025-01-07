<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Documento;
use App\Models\Expediente;

class ClientController extends Controller
{
    protected $attributes = [];
    public function index()
    {
        $clients = Client::orderBy('id', 'desc')->paginate(30);
        return view('client.index', compact('clients'));
    }

    public function getDataClientes(Request $request)
    {
        $show_all = $request->input('show_all', 0);
        $search = $request->input('search.value');
    
        $query = Client::select('id', 'nombre_de_cliente', 'telefono', 'email', 'direccion', 'pais', 'estatus');
    
        if (!$show_all && !$search) {
            $query->whereRaw('1 = 0');
        }
    
        return datatables()->of($query)
            ->filter(function ($query) use ($search) {
                if ($search) {
                    $query->where(function($q) use ($search) {
                        $q->where('nombre_de_cliente', 'like', "%{$search}%")
                          ->orWhere('telefono', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%")
                          ->orWhere('direccion', 'like', "%{$search}%")
                          ->orWhere('pais', 'like', "%{$search}%")
                          ->orWhere('estatus', 'like', "%{$search}%");
                    });
                }
            })
            ->addColumn('acciones', function(Client $cliente) {
                return view('client.actions', compact('cliente'))->render();
            })
            ->rawColumns(['acciones'])
            ->toJson();
    }

    public function create()
    {
        $tipos = ['asilo', 'appel', 'permanente', 'erar', 'apadrinamiento', 'humanitaria', 'temporal'];
        return view('client.create', compact('tipos'));
    }

    public function store(Request $request)
    {
        $client = new Client();
        $client->nombre_de_cliente = $request->nombre_de_cliente;
        $client->otros_nombres_de_cliente = $request->otros_nombres_de_cliente;
        $client->direccion = $request->direccion;
        $client->telefono = $request->telefono;
        $client->email = $request->email;
        $client->profesion = $request->profesion;
        $client->pais = $request->pais;
        $client->lenguaje = $request->lenguaje;
        $client->estatus = $request->estatus;
        $client->observaciones = $request->observaciones;
        $client->iuc = $request->iuc;
        $client->save();

        return redirect("client/".$client->id."/edit")->with('guardadoCorrectamente', 'El cliente fue guardado correctamente');
    }

    public function show($clientId)
    {
        $client = Client::with([
            'expedientes.tipoExpediente',
            'expedientes.honorarios',
            'documentos.tipoDocumento',
            'bitacoras' => function($query) {
                $query->latest()->take(5);
            },
            'documentos' => function($query) {
                $query->latest()->take(5);
            }
        ])->findOrFail($clientId);

        $expedientesActivos = $client->expedientes()
            ->where('estatus_del_expediente', 'Abierto')
            ->get();

        $totalHonorarios = $client->total_honorarios;
        $totalPagado = $client->total_pagado;
        $saldoPendiente = $client->saldo_pendiente;

        return view('client.show', compact(
            'client',
            'expedientesActivos',
            'totalHonorarios',
            'totalPagado',
            'saldoPendiente'
        ));
    }

    public function edit($clientId)
    {
        $client = Client::findOrFail($clientId);
        return view('client.edit', compact('client'));
    }

    public function update(Request $request, $clientId)
    {
        $client = Client::findOrFail($clientId);

        $client->nombre_de_cliente = $request->nombre_de_cliente;
        $client->otros_nombres_de_cliente = $request->otros_nombres_de_cliente;
        $client->direccion = $request->direccion;
        $client->telefono = $request->telefono;
        $client->email = $request->email;
        $client->profesion = $request->profesion;
        $client->pais = $request->pais;
        $client->lenguaje = $request->lenguaje;
        $client->estatus = $request->estatus;
        $client->observaciones = $request->observaciones;
        $client->iuc = $request->iuc;
        $client->save();

        return redirect("/client/{$client->id}");
    }

    public function destroy($clientId)
    {
        $client = Client::findOrFail($clientId);
        $client->delete();
        return redirect('/client');
    }

    public function getFamiliaAttribute($value)
{
    return $value ? json_decode($value, true) : [];
}

public function setFamiliaAttribute($value)
{
    $this->attributes['familia'] = json_encode($value);
}

public function addFamiliar(Request $request, Client $client)
{
    $familiar = $request->validate([
        'nombre' => 'required|string',
        'apellido' => 'required|string',
        'parentesco' => 'required|string',
    ]);

    $familia = $client->familia;
    $familia[] = $familiar;
    $client->familia = $familia;
    $client->save();

    return redirect()->back()->with('success', 'Familiar agregado con éxito');
}

}