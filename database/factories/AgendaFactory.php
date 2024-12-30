<?php

namespace Database\Factories;

use App\Models\Agenda;
use App\Models\User;
use App\Models\Expediente;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgendaFactory extends Factory
{
    protected $model = Agenda::class;

    public function definition(): array
    {
        return [
            'usuario_id' => User::factory(),
            'expediente_id' => Expediente::factory(),
            'titulo' => $this->faker->sentence(),
            'descripcion' => $this->faker->paragraph(),
            'fecha_de_inicio' => $this->faker->dateTimeBetween('now', '+1 week'),
            'fecha_de_fin' => $this->faker->dateTimeBetween('+1 week', '+2 weeks'),
        ];
    }
}
