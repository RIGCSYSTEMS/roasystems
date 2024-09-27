<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class callController extends Controller
{
    public function index()
    {
        return view('call.index');
    }

    public  function creacion()
    {
        return view('call.create');
    }
    //  public  function edicion()
    //  {
    //         return "Bienvenido a la página de edicion de llamadas ROA";
    //  }

     public  function show($id)
     {
            return view('call.show', ['id' => $id]);
     }
}
