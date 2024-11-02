<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class HumanitariaFactory extends Factory
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
            'fecha_de_recepcion' => $this->faker->date(),
            'observaciones' => $this->faker->text(),
            'tiempo' => $this->faker->time(),
            'persona_responsable' => $this->faker->name(),
        ];
    }
}
