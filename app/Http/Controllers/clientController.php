<?php

namespace App\Http\Controllers;

use App\Models\client;
use Illuminate\Http\Request;

class clientController extends Controller
{
    public function index()
    {

        $clients = client::all();

        return view('client.index',compact('clients'));
    }

    public function create()
    {
        return view('client.create');
    }

    public function store()
    {
        return request()->all();
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
}
