<?php

namespace App\Http\Controllers;

use App\Models\Expediente;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;
class ExpedienteController extends Controller
{
    public function index()
    {
//
    }

    public function create(Request $request)
    {
//
    }

    public function store(Request $request)
    {
//
    }

    // public function show(Expediente $expediente)
    public function show($id)
    {
        $expediente = Expediente::findOrFail($id);
        return view('expedientes.show', [
            'expedienteId' => $id,
            'expediente' => $expediente
        ]);

    }

    public function edit(Expediente $expediente)
    {
//
    }

    public function update(Request $request, Expediente $expediente)
    {
//
    }

    public function destroy(Expediente $expediente)
    {
//
    }
    
}