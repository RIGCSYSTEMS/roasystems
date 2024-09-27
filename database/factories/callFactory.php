<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\call>
 */
class callFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            
            'fecha' => $this->faker->date(),
            'nombre' => $this->faker->word(),
            'telefono' => $this->faker->word(),
            'aboagado' => $this->faker->word(),
            'motivo' => $this->faker->word(),
            'nota' => $this->faker->word(),
            'requiere_accion' => $this->faker->word(),

        ];
    }
}
