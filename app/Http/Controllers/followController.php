<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class followController extends Controller
{
    public function index()
    {
        return view('follow.index');
    }

    public  function creacion()
    {
        return view('follow.create');
    }
    //  public  function edicion()
    //  {
    //         return "Bienvenido a la página de edicion de seguimientos ROA";
    //  }

     public  function show($id)
     {
            return view('follow.show', ['id' => $id]);
     }
}
