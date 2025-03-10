<?php
// Archivo: app/Models/BitacoraCategoria.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BitacoraCategoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'activo',
    ];

    /**
     * Obtener las bitácoras que pertenecen a esta categoría.
     */
    public function bitacoras(): HasMany
    {
        return $this->hasMany(Bitacora::class, 'categoria_id');
    }

    /**
     * Obtener las actualizaciones de bitácora que pertenecen a esta categoría.
     */
    public function actualizaciones(): HasMany
    {
        return $this->hasMany(BitacoraActualizacion::class, 'categoria_id');
    }
}