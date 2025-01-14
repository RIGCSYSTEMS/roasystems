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
            'nombre_de_cliente' => $this->faker->name,
            'familia' => implode(', ', array_map(fn() => $this->faker->name, range(1, rand(2, 5)))),
            'fecha_de_nacimiento' => $this->faker->date('Y-m-d'),
            'genero' => $this->faker->randomElement(['masculino', 'femenino', 'otro']),
            'estado_civil' => $this->faker->randomElement(['soltero', 'casado', 'divorciado', 'viudo', 'otro']),
            'pais' => $this->faker->country,
            'llegada_a_canada' => $this->faker->date('Y-m-d'),
            'punto_de_acceso' => $this->faker->randomElement(['aeropuerto', 'terrestre', 'maritimo', 'otro']),
            'pasaporte' => $this->faker->numerify('A######'),
            'estatus' => $this->faker->randomElement(['activo', 'inactivo']),
            'direccion' => $this->faker->address,
            'telefono' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'profesion' => $this->faker->jobTitle,
            'lenguaje' => $this->faker->randomElement(['español', 'inglés', 'francés']),
            'permiso_de_trabajo' => $this->faker->numerify('A######'),
            'iuc' => $this->faker->numerify('A######'),
            'observaciones' => $this->faker->sentence,
        ];
    }
}
