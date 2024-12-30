<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'entidad_id',
        'entidad_tipo',
        'descripcion',
        'tiempo_empleado',
        'fecha_y_hora_del_evento',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
