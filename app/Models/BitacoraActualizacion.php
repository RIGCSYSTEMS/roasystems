<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BitacoraActualizacion extends Model
{
    use HasFactory;

    // Especificar explícitamente el nombre de la tabla
    protected $table = 'bitacora_actualizaciones';

    protected $fillable = [
        'bitacora_id',
        'user_id',
        'categoria_id',
        'descripcion',
        'tiempo_dedicado',
        'estado',
        'es_comentario',
    ];

    protected $casts = [
        'es_comentario' => 'boolean',
    ];

    /**
     * Obtener la bitácora a la que pertenece esta actualización
     */
    public function bitacora(): BelongsTo
    {
        return $this->belongsTo(Bitacora::class);
    }

    /**
     * Obtener el usuario que creó esta actualización
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Obtener la categoría de esta actualización
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(BitacoraCategoria::class, 'categoria_id');
    }
}

