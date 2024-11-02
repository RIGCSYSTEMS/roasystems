<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermanenteExpediente extends Model
{
    use HasFactory;
    protected $table = 'permanente_expedientes';
    protected $fillable = [
        'fecha',
        'fecha_de_recepcion',
        'observaciones',
        'tiempo',
        'persona_responsable',
    ];
}
