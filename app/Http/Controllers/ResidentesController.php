<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResidentesController extends Controller
{
    public function index()
    {
        return "Bienvenido a la página de Residentes Temporales";
    }

    public  function creacion()
    {
        return "Bienvenido a la página de creacion de Residentes Temporales ROA";
    }
     public  function edicion()
     {
            return "Bienvenido a la página de edicion de Residentes Temporales ROA";
     }

     public  function show($id)
     {
            return "Bienvenido a la página de Residentes Temporales con ID {$id}";
     }
}
