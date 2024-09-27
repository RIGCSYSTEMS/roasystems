<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResidentController extends Controller
{
    public function index()
    {
        return view('resident.index');
    }

    public  function creacion()
    {
        return view('resident.create');
    }
    //  public  function edicion()
    //  {
    //         return "Bienvenido a la página de edicion de Residentes Temporales ROA";
    //  }

     public  function show($id)
     {
            return view('resident.show', ['id' => $id]);
     }
}
