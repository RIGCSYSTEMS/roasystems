<?php
// Archivo: app/Models/BitacoraHistorialEstado.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BitacoraHistorialEstado extends Model
{
    use HasFactory;

    protected $fillable = [
        'bitacora_id',
        'user_id',
        'accion',
        'fecha',
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    /**
     * Obtener la bitácora a la que pertenece este historial.
     */
    public function bitacora(): BelongsTo
    {
        return $this->belongsTo(Bitacora::class);
    }

    /**
     * Obtener el usuario que realizó esta acción.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}