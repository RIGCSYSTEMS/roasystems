<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AsiloFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fecha' => $this->faker->name(),
            'fecha_de_recepcion' => $this->faker->text(),
            'observaciones' => $this->faker->address(),
            'tiempo' => $this->faker->phoneNumber(),
            'persona_responsable' => $this->faker->email(),
        ];
    }
}
