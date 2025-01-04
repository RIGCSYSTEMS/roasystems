<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAudienciasRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'fecha_hora' => 'required|date',
            'tipo_audiencia' => 'required|string',
            'descripcion' => 'nullable|string',
            'lugar' => 'required|string',
            'responsable' => 'nullable|string',
            'notas_internas' => 'nullable|string',
        ];
    }
}
