<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LlamadasController extends Controller
{
    public function index()
    {
        return "Bienvenido a la página de llamadas";
    }

    public  function creacion()
    {
        return "Bienvenido a la página de creacion de llamadas ROA";
    }
     public  function edicion()
     {
            return "Bienvenido a la página de edicion de llamadas ROA";
     }

     public  function show($id)
     {
            return "Bienvenido a la página de llamadas con ID {$id}";
     }
}
