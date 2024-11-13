<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Expediente;
use App\Models\Documento; // Ensure that the Documento class exists in this namespace

class client extends Model
{
    use HasFactory;

    protected $table = 'client';
    protected $fillable = [
        'nombre_del_cliente',
        'otros_nombre_de_familia',
        'direccion',
        'telefono',
        'email',
        'pais',
        'lenguaje',
        'profesion',
        'permiso_de_trabajo',
        'iuc',
        'estatus',
        'observaciones'
    ];

    public function expedientes()
    {
        return $this->hasMany(Expediente::class);
    }

    public function documentos(): HasMany
    {

        return $this->hasMany(Documento::class);
    }
}
