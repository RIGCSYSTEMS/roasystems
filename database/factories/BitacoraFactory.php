<?php

namespace Database\Factories;

use App\Models\Bitacora;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BitacoraFactory extends Factory
{
    protected $model = Bitacora::class;

    public function definition(): array
    {
        return [
            'usuario_id' => User::factory(),
            'entidad_id' => $this->faker->randomNumber(),
            'entidad_tipo' => $this->faker->randomElement(['expediente', 'documento', 'agenda']),
            'descripcion' => $this->faker->paragraph(),
            'tiempo_empleado' => $this->faker->time(),
            'fecha_y_hora_del_evento' => $this->faker->dateTime(),
        ];
    }
}
