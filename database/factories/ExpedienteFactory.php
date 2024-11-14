<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expediente>
 */
class ExpedienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            
            'client_id' => client::factory(),
            'fecha_de apertura' => $this->faker->date(),
            'fecha_de cierre' => $this->faker->date(),
            'estatus_del_expediente' => $this->faker->text(),
            'numero_de_dossier' => $this->faker->text(),
            'despacho' => $this->faker->text(),
            'abogado' => $this->faker->text(),
            'honorarios' => $this->faker->randomFloat(2, 0, 999999.99),
            'tipo' => $this->faker->text(),
            
            // 'fecha' => $this->faker->date(),
            // 'fecha_de_recepcion' => $this->faker->date(),
            // 'observaciones' => $this->faker->text(),
            // 'tiempo' => $this->faker->time(),
            // 'persona_responsable' => $this->faker->name(),
        ];
    }
}
