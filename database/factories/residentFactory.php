<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\resident>
 */
class residentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            
            'id_cliente' => $this->faker->numberBetween(1, 100),
            'fecha_de_demanda' => $this->faker->date(),
            'tipo_de_demanda' => $this->faker->word(),
            'usuario' => $this->faker->word(),
            'password' => $this->faker->word(),
            'preguntas_respuestas' => $this->faker->text(),
            'despacho' => $this->faker->word(),
            'abogado' => $this->faker->word(),
            'confirmacion' => $this->faker->word(),
            'notas' => $this->faker->text(),

        ];
    }
}


// $table->id();
// $table->string('id_cliente');
// $table->string('fecha_de_demanda');
// $table->string('tipo_de_demanda');
// $table->string('usuario');
// $table->string('password');
// $table->longText('preguntas_respuestas');
// $table->string('despacho');
// $table->string('abogado');
// $table->string('confirmacion');
// $table->longText('notas');
// $table->timestamps();