<?php

namespace Database\Factories;

use App\Models\BitacoraHistorialEstado;
use App\Models\Bitacora;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class BitacoraHistorialEstadoFactory extends Factory
{
    protected $model = BitacoraHistorialEstado::class;

    public function definition(): array
    {
        $acciones = [
            'Creación de bitácora',
            'Marcada como completada',
            'Marcada como en proceso',
            'Marcada como pendiente',
            'Reactivada',
            'Actualización de información',
        ];

        // Usar Carbon para manejar fechas de manera consistente
        $now = Carbon::now('UTC');
        $sixMonthsAgo = $now->copy()->subMonths(6);
        
        // Fecha entre hace 6 meses y hace 1 minuto
        $fecha = Carbon::createFromTimestamp(
            mt_rand($sixMonthsAgo->timestamp, $now->copy()->subMinute()->timestamp)
        )->setTimezone('UTC');

        return [
            'bitacora_id' => Bitacora::factory(),
            'user_id' => User::factory(),
            'accion' => $this->faker->randomElement($acciones),
            'fecha' => $fecha,
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }

    /**
     * Configurar el historial como creación
     */
    public function creacion(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'accion' => 'Creación de bitácora',
            ];
        });
    }

    /**
     * Configurar el historial como completado
     */
    public function completado(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'accion' => 'Marcada como completada',
            ];
        });
    }

    /**
     * Configurar el historial como reactivación
     */
    public function reactivacion(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'accion' => 'Reactivada',
            ];
        });
    }
}

