<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppelExpediente extends Model
{
    use HasFactory;
    protected $table = 'appel_expedientes';
    protected $fillable = [
        'fecha',
        'fecha_de_recepcion',
        'observaciones',
        'tiempo',
        'persona_responsable',
    ];
}
