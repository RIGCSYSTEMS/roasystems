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


    public function getDataClientes(Request $request)
    {
        $search_active = $request->input('search_active', 0);
    
        $clientes = client::select('id', 'nombre_de_cliente', 'telefono', 'email', 'direccion', 'pais', 'fecha_de_apertura', 'fecha_de_cierre', 'estatus');
    
        if ($search_active) {
            return datatables()->of($clientes)
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && $request->search['value']) {
                        $query->where('nombre_de_cliente', 'like', "%{$request->search['value']}%");
                    }
                })
                ->addColumn('nombre', function(client $cliente) {
                    return $cliente->nombre_de_cliente;
                })
                ->addColumn('correo', function(client $cliente) {
                    return $cliente->email;
                })
                ->addColumn('fecha_apertura', function(client $cliente) {
                    return $cliente->fecha_de_apertura;
                })
                ->addColumn('fecha_cierre', function(client $cliente) {
                    return $cliente->fecha_de_cierre;
                })
                ->addColumn('acciones', function(client $cliente) {
                    $action = '<button type="button" class="btn btn-info btn-sm" title="Editar Cliente" onclick="editarCliente('.$cliente->id.')"><i class="fa fa-edit"></i> Editar</button>';
                    $action .= ' <button type="button" class="btn btn-danger btn-sm" title="Eliminar" onclick="eliminarCliente('.$cliente->id.')"><i class="fa fa-trash"></i> Eliminar</button>';
                    return $action;
                })
                ->rawColumns(['acciones'])
                ->with(['search_active' => $search_active])
                ->toJson();
        } else {
            return datatables()->of(collect([]))->with(['search_active' => $search_active])->toJson();
        }
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

      //  return redirect('/client');
      return redirect("client/".$client->id."/edit")->with('guardadoCorrectamente', 'El cliente fue guardado corrrectamente');


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
