<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LlamadasController extends Controller
{
    public function index()
    {
        return view('llamadas.index');
    }

    public  function creacion()
    {
        return view('llamadas.create');
    }
    //  public  function edicion()
    //  {
    //         return "Bienvenido a la página de edicion de llamadas ROA";
    //  }

     public  function show($id)
     {
            return view('llamadas.show', ['id' => $id]);
     }
}
