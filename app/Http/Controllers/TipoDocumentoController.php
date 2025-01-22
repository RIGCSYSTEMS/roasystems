<?php

namespace App\Http\Controllers;

use App\Models\TipoDocumento;
use Illuminate\Http\Request;

class TipoDocumentoController extends Controller
{
    public function index()
    {
        $tiposDocumento = TipoDocumento::all();
        return response()->json($tiposDocumento);
    }
}