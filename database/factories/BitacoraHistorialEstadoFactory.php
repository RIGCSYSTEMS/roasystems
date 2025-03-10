<?php

namespace Database\Factories;

use App\Models\BitacoraHistorialEstado;
use App\Models\Bitacora;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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

        return [
            'bitacora_id' => Bitacora::factory(),
            'user_id' => User::factory(),
            'accion' => $this->faker->randomElement($acciones),
            'fecha' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'created_at' => now(),
            'updated_at' => now(),
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

