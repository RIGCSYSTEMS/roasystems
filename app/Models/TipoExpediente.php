<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoExpediente extends Model
{
    use HasFactory;
    protected $fillable = [
        'expediente_id',
        'fecha',
        'fecha_de_recepcion',
        'observaciones',
        'tiempo',
        'persona_responsible'
    ];

    public function expediente()
    {
        return $this->belongsTo(Expediente::class);
    }
}
