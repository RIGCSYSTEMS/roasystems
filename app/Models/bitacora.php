<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    use HasFactory;

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
}
