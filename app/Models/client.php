<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class client extends Model
{
    use HasFactory;

    protected $table = 'client';

    // protected $fillable = [
    //     'nombre_de_cliente',
    //     'otros_nombres_de_cliente',
    //     'direccion',
    //     'telefono',
    //     'email',
    //     'profesion',
    //     'pais',
    //     'lenguaje',
    //     'permiso_de_trabajo',
    //     'iuc',
    //     'observaciones'
    // ];
}
