<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Documento extends Model
{
    use HasFactory;


    protected $fillable = [

        'nombre_de_documento',
        'tipo_de_documento',
        'imagen_url',
        'client_id',
        'observaciones',
 
 
 
        // 'identificacion',
        // 'pasaporte',
        // 'permiso_de_trabajo',
        // 'hoja_marron',
        // 'pruebas',
        // 'historia',
        // 'residencia_permanente',
        // 'caq',
        // 'extras'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
