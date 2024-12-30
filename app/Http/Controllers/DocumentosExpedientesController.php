<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentoExpediente;
use App\Models\EtapaExpediente;
use App\Models\Expediente;

class DocumentoExpedienteController extends Controller
{
    public function validarDocumento(Request $request, $idDocumento)
    {
        $documento = DocumentoExpediente::findOrFail($idDocumento);
        $documento->validado = true;
        $documento->save();

        // Verificar etapa
        $etapa = $documento->etapa;
        $etapa->verificarCompletada();

        // Actualizar progreso del expediente
        $expediente = $etapa->expediente;
        $expediente->progreso = $expediente->calcularProgreso();
        $expediente->save();

        return response()->json([
            'success' => true,
            'etapa_completada' => $etapa->completada,
            'progreso' => $expediente->progreso
        ]);
    }
}