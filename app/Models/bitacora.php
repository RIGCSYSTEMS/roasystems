<?php
// Archivo: app/Models/Bitacora.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bitacora extends Model
{
    use HasFactory;

    protected $fillable = [
        'expediente_id',
        'user_id',
        'tipo',
        'titulo',
        'categoria_id',
        'descripcion',
        'tiempo_dedicado',
        'estado',
        'fecha_limite',
        'prioridad_fecha',
        'fecha_completado',
        'fecha_reactivacion',
    ];

    protected $casts = [
        'fecha_limite' => 'date',
        'fecha_completado' => 'datetime',
        'fecha_reactivacion' => 'datetime',
    ];

    /**
     * Obtener el expediente al que pertenece esta bitácora.
     */
    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class);
    }

    /**
     * Obtener el usuario que creó esta bitácora.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Obtener la categoría de esta bitácora.
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(BitacoraCategoria::class, 'categoria_id');
    }

    /**
     * Obtener las actualizaciones de esta bitácora.
     */
    public function actualizaciones(): HasMany
    {
        return $this->hasMany(BitacoraActualizacion::class);
    }

    /**
     * Obtener el historial de estados de esta bitácora.
     */
    public function historialEstados(): HasMany
    {
        return $this->hasMany(BitacoraHistorialEstado::class);
    }

    /**
     * Verificar si la bitácora está próxima a vencer.
     */
    public function estaProximaAVencer(): bool
    {
        if (!$this->fecha_limite || $this->estado === 'completado') {
            return false;
        }

        $diasRestantes = $this->fecha_limite->diffInDays(now(), false);
        
        if ($diasRestantes < 0) {
            return false; // Ya venció
        }

        switch ($this->prioridad_fecha) {
            case 'critica':
                return $diasRestantes <= 7;
            case 'importante':
                return $diasRestantes <= 5;
            default:
                return $diasRestantes <= 3;
        }
    }

    /**
     * Verificar si la bitácora está vencida.
     */
    public function estaVencida(): bool
    {
        if (!$this->fecha_limite || $this->estado === 'completado') {
            return false;
        }

        return now()->greaterThan($this->fecha_limite);
    }

    /**
     * Calcular el porcentaje de progreso hacia la fecha límite.
     */
    public function calcularPorcentajeProgreso(): int
    {
        if (!$this->fecha_limite) {
            return 0;
        }

        if ($this->estado === 'completado') {
            return 100;
        }

        $fechaInicio = $this->created_at;
        $fechaFin = $this->fecha_limite;
        $hoy = now();

        $totalDias = $fechaInicio->diffInDays($fechaFin);
        if ($totalDias === 0) {
            return 100; // Evitar división por cero
        }

        $diasTranscurridos = $fechaInicio->diffInDays($hoy);
        $porcentaje = round(($diasTranscurridos / $totalDias) * 100);

        return max(0, min(100, $porcentaje));
    }
}