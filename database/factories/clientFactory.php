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
            // 'despacho' => $this->faker->word(),
            // 'tipo_de_expediente' => $this->faker->word(),
            'lenguaje' => $this->faker->word(),
            // 'honorarios' => $this->faker->word(),
            // 'fecha_de_apertura' => $this->faker->date(),
            // 'estatus' => $this->faker->word(),
            'observaciones' => $this->faker->text(),
            // 'numero_de_expediente' => $this->faker->word(),
            'permiso_de_trabajo' => $this->faker->word(),
            'IUC' => $this->faker->word(),
            // 'ubicacion_del_despacho' => $this->faker->word(),
            // 'fecha_de_cierre' => $this->faker->date(),
            // 'cierre_del_numero_de_caja' => $this->faker->word(),
            
        ];
    }
}
