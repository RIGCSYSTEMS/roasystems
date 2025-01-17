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
            'familia' => $this->faker->randomElements([
    ['nombre' => $this->faker->firstName(), 'parentesco' => 'hermana'],
    ['nombre' => $this->faker->firstName(), 'parentesco' => 'hermano'],
    ['nombre' => $this->faker->firstName(), 'parentesco' => 'sobrino'],
    ['nombre' => $this->faker->firstName(), 'parentesco' => 'sobrina'],
    ['nombre' => $this->faker->firstName(), 'parentesco' => 'padre'],
    ['nombre' => $this->faker->firstName(), 'parentesco' => 'madre'],
    ['nombre' => $this->faker->firstName(), 'parentesco' => 'abuelo'],
    ['nombre' => $this->faker->firstName(), 'parentesco' => 'abuela'],
    ['nombre' => $this->faker->firstName(), 'parentesco' => 'tío'],
    ['nombre' => $this->faker->firstName(), 'parentesco' => 'tía'],
    ['nombre' => $this->faker->firstName(), 'parentesco' => 'primo'],
    ['nombre' => $this->faker->firstName(), 'parentesco' => 'prima'],
    ['nombre' => $this->faker->firstName(), 'parentesco' => 'hijo'],
    ['nombre' => $this->faker->firstName(), 'parentesco' => 'hija'],
    ['nombre' => $this->faker->firstName(), 'parentesco' => 'nieto'],
    ['nombre' => $this->faker->firstName(), 'parentesco' => 'nieta'],
    ['nombre' => $this->faker->firstName(), 'parentesco' => 'esposo'],
    ['nombre' => $this->faker->firstName(), 'parentesco' => 'esposa'],
    ['nombre' => $this->faker->firstName(), 'parentesco' => 'cuñado'],
    ['nombre' => $this->faker->firstName(), 'parentesco' => 'cuñada'],
    ['nombre' => $this->faker->firstName(), 'parentesco' => 'suegro'],
    ['nombre' => $this->faker->firstName(), 'parentesco' => 'suegra'],
    ['nombre' => $this->faker->firstName(), 'parentesco' => 'yerno'],
    ['nombre' => $this->faker->firstName(), 'parentesco' => 'nuera'],
    ['nombre' => $this->faker->firstName(), 'parentesco' => 'otro'],
], $this->faker->numberBetween(0, 5)),
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
