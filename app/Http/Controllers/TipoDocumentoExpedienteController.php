<?php

namespace App\Http\Controllers;

use App\Models\TipoDocumento;
use App\Models\TipoDocumentoExpediente;
use Illuminate\Http\Request;

class TipoDocumentoExpedienteController extends Controller
{
    public function index()
    {
        $tiposDocumentoExp = TipoDocumentoExpediente::all();
        return response()->json($tiposDocumentoExp);
    }
}