<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\follow>
 */
class followFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            
            'id_client' => $this->faker->numberBetween(1, 100),
            'fecha' => $this->faker->date(),
            'comentarios' => $this->faker->text(),
            'fecha_recepcion' => $this->faker->date(),
            'fecha_limite' => $this->faker->date(),
            'tiempo' => $this->faker->word(),
            'estatus' => $this->faker->word(),
            'persona_responsable' => $this->faker->word(),

        ];
    }
}



// $follow->id = '1';
//         $follow->id_client = '1';
//         $follow->fecha = '2021-10-10';
//         $follow->comentarios = 'Comentarios 1';
//         $follow->fecha_recepcion = '2021-10-10';
//         $follow->fecha_limite = '2021-10-10';
//         $follow->tiempo = '1 minuto';
//         $follow->estatus = 'activo';
//         $follow->persona_responsable = 'carolina';