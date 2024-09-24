<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SeguimientosController extends Controller
{
    public function index()
    {
        return "Bienvenido a la página de seguimientos";
    }

    public  function creacion()
    {
        return "Bienvenido a la página de creacion de seguimientos ROA";
    }
     public  function edicion()
     {
            return "Bienvenido a la página de edicion de seguimientos ROA";
     }

     public  function show($id)
     {
            return "Bienvenido a la página de seguimientos con ID {$id}";
     }
}
