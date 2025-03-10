<?php

namespace Database\Factories;

use App\Models\BitacoraActualizacion;
use App\Models\BitacoraCategoria;
use App\Models\Bitacora;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BitacoraActualizacionFactory extends Factory
{
    protected $model = BitacoraActualizacion::class;

    public function definition(): array
    {
        $esComentario = $this->faker->boolean(30);
        $estado = $esComentario ? 'comentario' : $this->faker->randomElement(['completado', 'en_proceso', 'pendiente']);

        return [
            'bitacora_id' => Bitacora::factory(),
            'user_id' => User::factory(),
            'categoria_id' => BitacoraCategoria::factory(),
            'descripcion' => $this->faker->paragraph(),
            'tiempo_dedicado' => $this->faker->numberBetween(10, 120),
            'estado' => $estado,
            'es_comentario' => $esComentario,
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }

    /**
     * Configurar la actualización como comentario
     */
    public function comentario(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'estado' => 'comentario',
                'es_comentario' => true,
            ];
        });
    }

    /**
     * Configurar la actualización como completada
     */
    public function completada(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'estado' => 'completado',
                'es_comentario' => false,
            ];
        });
    }
}

