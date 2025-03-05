<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Prunable;

class Bitacora extends Model
{
    use HasFactory, SoftDeletes, Prunable;

    protected $fillable = [
        'usuario_id',
        'expediente_id',
        'categoria',
        'descripcion',
        'tiempo_empleado',
        'fecha_y_hora_del_evento',
    ];
    
    public function expediente()
    {
        return $this->belongsTo(Expediente::class);
    }
    
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function prunable()
    {
        return static::where('deleted_at', '<=', now()->subYears(7));
    }
}
