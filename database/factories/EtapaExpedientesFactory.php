<?php

namespace Database\Factories;

use App\Models\EtapaExpediente;
use App\Models\Expediente;
use Illuminate\Database\Eloquent\Factories\Factory;

class EtapaExpedienteFactory extends Factory
{
    protected $model = EtapaExpediente::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->word(),
            'expediente_id' => Expediente::factory(), // Relación con un expediente
            'completada' => $this->faker->boolean(50), // True o False al azar
        ];
    }
}
