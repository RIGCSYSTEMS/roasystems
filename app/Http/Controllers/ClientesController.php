<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientesController extends Controller
{
    public function index()
    {
        return "Bienvenido a la página de clientes";
    }

    public function creacion()
    {
        return "Bienvenido a la página de creación de clientes ROA";
    }

    public function edicion()
    {
        return "Bienvenido a la página de edicion de clientes ROA";
    }

    public function show($id)
    {
        return "Bienvenido a la página de clientes con ID {$id}";
    }
}
