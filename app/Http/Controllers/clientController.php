<?php

namespace App\Http\Controllers;

use App\Models\client;
use Illuminate\Http\Request;

class clientController extends Controller
{
    public function index()
    {

        $clients = client::orderBy('id', 'desc')
                        ->paginate(30);

        return view('client.index',compact('clients'));
    }

    public function create()
    {
        return view('client.create');
    }

    public function store(Request $request)
    {
        $client = new client();

        $client->nombre_de_cliente = $request->nombre_de_cliente;
        $client->otros_nombres_de_cliente = $request->otros_nombres_de_cliente;
        $client->direccion = $request->direccion;
        $client->telefono = $request->telefono;
        $client->email = $request->email;
        $client->profesion = $request->profesion;
        $client->pais = $request->pais;
        $client->despacho = $request->despacho;
        $client->tipo_de_expediente = $request->tipo_de_expediente;
        $client->lenguaje = $request->lenguaje;
        $client->honorarios = $request->honorarios;
        $client->fecha_de_apertura = $request->fecha_de_apertura;
        $client->estatus = $request->estatus;
        $client->observaciones = $request->observaciones;
        $client->numero_de_expediente = $request->numero_de_expediente;
        $client->permiso_de_trabajo = $request->permiso_de_trabajo;
        $client->iuc = $request->iuc;
        $client->ubicacion_del_despacho = $request->ubicacion_del_despacho;
        $client->fecha_de_cierre = $request->fecha_de_cierre;
        $client->cierre_del_numero_de_caja = $request->cierre_del_numero_de_caja;

        $client->save();

        return redirect('/client');
    }
    // public function edicion()
    // {
    //     return view('clientes.edicion');
    // }

    public function show($client)
    {
        $client = client::find($client);

        return view('client.show', ['client' => $client]);
    }

    public function edit($client)
    {
        $client = client::find($client);

        return view('client.edit', compact('client'));
    }

    public function update(Request $request, $client)
    {

        
        $client = client::find($client);

        $client->nombre_de_cliente = $request->nombre_de_cliente;
        $client->otros_nombres_de_cliente = $request->otros_nombres_de_cliente;
        $client->direccion = $request->direccion;
        $client->telefono = $request->telefono;
        $client->email = $request->email;
        $client->profesion = $request->profesion;
        $client->pais = $request->pais;
        $client->despacho = $request->despacho;
        $client->tipo_de_expediente = $request->tipo_de_expediente;
        $client->lenguaje = $request->lenguaje;
        $client->honorarios = $request->honorarios;
        $client->fecha_de_apertura = $request->fecha_de_apertura;
        $client->estatus = $request->estatus;
        $client->observaciones = $request->observaciones;
        $client->numero_de_expediente = $request->numero_de_expediente;
        $client->permiso_de_trabajo = $request->permiso_de_trabajo;
        $client->iuc = $request->iuc;
        $client->ubicacion_del_despacho = $request->ubicacion_del_despacho;
        $client->fecha_de_cierre = $request->fecha_de_cierre;
        $client->cierre_del_numero_de_caja = $request->cierre_del_numero_de_caja;

        $client->save();

        return redirect("/client/{$client->id}");
    }

    public function destroy($client)
    {
        $client = client::find($client);

        $client->delete();

        return redirect('/client');
    }

}
