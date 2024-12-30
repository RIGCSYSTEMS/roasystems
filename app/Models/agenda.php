<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'expediente_id',
        'titulo',
        'descripcion',
        'fecha_de_inicio',
        'fecha_de_fin',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function expediente()
    {
        return $this->belongsTo(Expediente::class);
    }
}
