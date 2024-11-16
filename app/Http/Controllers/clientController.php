<?php

namespace App\Http\Controllers;

use App\Models\client;
use Illuminate\Http\Request;
use App\Models\Documento;

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
    
        $clientes = client::select('id', 'nombre_de_cliente', 'telefono', 'email', 'direccion', 'pais', 'estatus');
    
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
        {
            $query = Client::query();
        
            if ($request->has('search') && $request->search['value'] != '') {
                $searchValue = $request->search['value'];
                $query->where(function($q) use ($searchValue) {
                    $q->where('nombre_de_cliente', 'like', "%{$searchValue}%")
                      ->orWhere('telefono', 'like', "%{$searchValue}%")
                      ->orWhere('email', 'like', "%{$searchValue}%")
                      ->orWhere('direccion', 'like', "%{$searchValue}%")
                      ->orWhere('pais', 'like', "%{$searchValue}%")
                      ->orWhere('estatus', 'like', "%{$searchValue}%");
                    });
                }
        
            return datatables()->of($query)
                ->addColumn('acciones', function($row) {
                    // Aquí puedes generar los botones de acción
                    return '<button>Editar</button> <button>Eliminar</button>';
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }
    }

    public function create()
    {
        // return view('client.create');
        $tipos = ['asilo', 'appel', 'permanente', 'erar', 'apadrinamiento', 'humanitaria', 'temporal'];
    return view('client.create', compact('tipos'));
    }

    public function store(Request $request)
    {
        //primera version de creacion de cliente
        $client = new client();
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
        $client->lenguaje = $request->lenguaje;
        $client->estatus = $request->estatus;
        $client->observaciones = $request->observaciones;
        $client->iuc = $request->iuc;
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
