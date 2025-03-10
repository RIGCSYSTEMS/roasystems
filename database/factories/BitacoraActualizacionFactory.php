<?php

namespace Database\Factories;

use App\Models\BitacoraActualizacion;
use App\Models\BitacoraCategoria;
use App\Models\Bitacora;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class BitacoraActualizacionFactory extends Factory
{
    protected $model = BitacoraActualizacion::class;

    public function definition(): array
    {
        $esComentario = $this->faker->boolean(30);
        $estado = $esComentario ? 'comentario' : $this->faker->randomElement(['completado', 'en_proceso', 'pendiente']);

        // Usar Carbon para manejar fechas de manera consistente
        $now = Carbon::now('UTC');
        $sixMonthsAgo = $now->copy()->subMonths(6);
        
        // Fecha de creación entre hace 6 meses y hace 1 hora
        $fechaCreacion = Carbon::createFromTimestamp(
            mt_rand($sixMonthsAgo->timestamp, $now->copy()->subHour()->timestamp)
        )->setTimezone('UTC');
        
        // Fecha de actualización entre la creación y hace 1 minuto
        $fechaActualizacion = Carbon::createFromTimestamp(
            mt_rand($fechaCreacion->timestamp, $now->copy()->subMinute()->timestamp)
        )->setTimezone('UTC');

        return [
            'bitacora_id' => Bitacora::factory(),
            'user_id' => User::factory(),
            'categoria_id' => BitacoraCategoria::factory(),
            'descripcion' => $this->faker->paragraph(),
            'tiempo_dedicado' => $this->faker->numberBetween(10, 120),
            'estado' => $estado,
            'es_comentario' => $esComentario,
            'created_at' => $fechaCreacion,
            'updated_at' => $fechaActualizacion,
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

