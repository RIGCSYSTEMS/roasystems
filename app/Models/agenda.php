<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Prunable;

class Agenda extends Model
{
    use HasFactory, SoftDeletes, Prunable;

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

    public function prunable()
    {
        return static::where('deleted_at', '<=', now()->subYears(7));
    }
}
