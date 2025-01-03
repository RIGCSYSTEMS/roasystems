<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audiencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'expediente_id',
        'fecha_hora',
        'tipo_audiencia',
        'descripcion',
        'lugar',
        'estado',
        'responsable',
        'notas_internas',
        'resultado'
    ];

    protected $casts = [
        'fecha_hora' => 'datetime'
    ];

    public function expediente()
    {
        return $this->belongsTo(Expediente::class);
    }
}