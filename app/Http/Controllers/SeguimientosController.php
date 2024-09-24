<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SeguimientosController extends Controller
{
    public function index()
    {
        return view('seguimientos.index');
    }

    public  function creacion()
    {
        return view('seguimientos.create');
    }
    //  public  function edicion()
    //  {
    //         return "Bienvenido a la página de edicion de seguimientos ROA";
    //  }

     public  function show($id)
     {
            return view('seguimientos.show', ['id' => $id]);
     }
}
