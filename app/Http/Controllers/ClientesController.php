<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientesController extends Controller
{
    public function index()
    {
        return view('clientes.index');
    }

    public function creacion()
    {
        return view('clientes.create');
    }

    // public function edicion()
    // {
    //     return view('clientes.edicion');
    // }

    public function show($id)
    {
        return view('clientes.show', ['id' => $id]);
    }
}
