<?php

namespace Database\Factories;

use App\Models\Bitacora;
use App\Models\User;
use App\Models\Expediente;
use Illuminate\Database\Eloquent\Factories\Factory;

class BitacoraFactory extends Factory
{
    protected $model = Bitacora::class;

    public function definition()
    {
        return [
            'usuario_id' => User::factory(),
            'expediente_id' => Expediente::factory(),
            'categoria' => $this->faker->randomElement([
                'Actualizacion de informacion',
                'Carga de documento',
                'Comunicacion con el cliente',
                'Audiencia',
                'Revision',
                'Otro'
            ]),
            'descripcion' => $this->faker->paragraph(),
            'tiempo_empleado' => $this->faker->time(),
            'fecha_y_hora_del_evento' => $this->faker->dateTimeThisYear(),
        ];
    }
}