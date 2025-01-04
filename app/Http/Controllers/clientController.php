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
        $show_all = $request->input('show_all', 0);
        $search = $request->input('search.value');
    
        $query = Client::select('id', 'nombre_de_cliente', 'telefono', 'email', 'direccion', 'pais', 'estatus');
    
        if (!$show_all && !$search) {
            $query->whereRaw('1 = 0'); // No mostrar nada si show_all es falso y no hay búsqueda
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
