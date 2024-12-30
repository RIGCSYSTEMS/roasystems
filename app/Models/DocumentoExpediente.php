<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentoExpediente extends Model
{
    use HasFactory;

    protected $fillable = [
        'expediente_id',
        'etapa_id',
        'nombre',
        'ruta',
        'tipo',
        'validado',
        'descripcion',
    ];

    public function etapa()
    {
        return $this->belongsTo(EtapaExpediente::class, 'etapa_id');
    }

    public function expediente()
    {
        return $this->belongsTo(Expediente::class, 'expediente_id');
    }
}