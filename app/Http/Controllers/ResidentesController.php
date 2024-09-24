<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResidentesController extends Controller
{
    public function index()
    {
        return view('residentes.index');
    }

    public  function creacion()
    {
        return view('residentes.create');
    }
    //  public  function edicion()
    //  {
    //         return "Bienvenido a la página de edicion de Residentes Temporales ROA";
    //  }

     public  function show($id)
     {
            return view('residentes.show', ['id' => $id]);
     }
}
