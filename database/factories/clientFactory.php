<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\client>
 */
class clientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre_de_cliente' => $this->faker->name(),
            'otros_nombres_de_cliente' => $this->faker->text(),
            'direccion' => $this->faker->address(),
            'telefono' => $this->faker->phoneNumber(),
            'email' => $this->faker->email(),
            'profesion' => $this->faker->word(),
            'pais' => $this->faker->country(),
            'estatus' => $this->faker->randomElement(['Activo', 'Inactivo']),
            'lenguaje' => $this->faker->word(),
            'observaciones' => $this->faker->text(),
            'permiso_de_trabajo' => $this->faker->word(),
            'IUC' => $this->faker->word(),
            
        ];
    }
}
