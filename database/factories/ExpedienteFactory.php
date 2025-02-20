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
            'tipo_expediente_id' => $this->faker
            ->randomElement(['ASILO', 'APPEL','RESIDENCIA PERMANENTE','ERAR','APADRINAMIENTO','HUMANITARIA','RESIDENCIA TEMPORAL']), // Ajustar según los tipos reales
            'numero_de_dossier' => $this->faker->unique()->uuid,
            'estatus_del_expediente' => $this->faker->randomElement(['Abierto', 'Cerrado', 'Cancelado']),
            'prioridad' => $this->faker->randomElement(['Urgente', 'Normal']),
            'fecha_de_apertura' => $this->faker->date,
            'fecha_de_cierre' => $this->faker->optional()->date,
            'despacho' => $this->faker->optional()->company,
            'abogado' => $this->faker->optional()->name,
            'plazo_fda' => $this->faker->optional()->date,
            'progreso' => $this->faker->numberBetween(0, 100),
            'boite' => $this->faker->randomElement(['Boite 1', 'Boite 2', 'Boite 3', 'Boite 4', 'Boite 5']),
            'observaciones' => $this->faker->optional()->text,

            // 'fecha_de apertura' => $this->faker->date(),
            // 'fecha_de cierre' => $this->faker->date(),
            // 'estatus_del_expediente' => $this->faker->text(),
            // 'numero_de_dossier' => $this->faker->text(),
            // 'despacho' => $this->faker->text(),
            // 'abogado' => $this->faker->text(),
            // 'honorarios' => $this->faker->randomFloat(2, 0, 999999.99),
            // 'tipo' => $this->faker->text(),
            
            // 'fecha' => $this->faker->date(),
            // 'fecha_de_recepcion' => $this->faker->date(),
            // 'observaciones' => $this->faker->text(),
            // 'tiempo' => $this->faker->time(),
            // 'persona_responsable' => $this->faker->name(),
        ];
    }
}
