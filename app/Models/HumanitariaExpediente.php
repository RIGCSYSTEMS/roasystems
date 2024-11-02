<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HumanitariaExpediente extends Model
{
    use HasFactory;
    protected $table = 'humanitaria_expedientes';
    protected $fillable = [
        'fecha',
        'fecha_de_recepcion',
        'observaciones',
        'tiempo',
        'persona_responsable',
    ];
}
