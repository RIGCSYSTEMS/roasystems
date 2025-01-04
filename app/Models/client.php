<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_de_cliente',
        'familia',
        'fecha_de_nacimiento',
        'genero',
        'estado_civil',
        'pais',
        'llegada_a_canada',
        'punto_de_acceso',
        'pasaporte',
        'estatus',
        'direccion',
        'telefono',
        'email',
        'profesion',
        'lenguaje',
        'permiso_de_trabajo',
        'iuc',
        'observaciones'
    ];

    protected $casts = [
        'fecha_de_nacimiento' => 'date',
        'llegada_a_canada' => 'date',
    ];

    public function expedientes(): HasMany
    {
        return $this->hasMany(Expediente::class);
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(Documento::class);
    }
}