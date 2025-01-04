<?php

namespace Database\Factories;

use App\Models\Audiencia;
use App\Models\Expediente;
use Illuminate\Database\Eloquent\Factories\Factory;

class AudienciaFactory extends Factory
{
    protected $model = Audiencia::class;

    public function definition()
    {
        return [
            'expediente_id' => Expediente::factory(),
            'fecha_hora' => $this->faker->dateTimeBetween('now', '+6 months'),
            'tipo_audiencia' => $this->faker->randomElement(['Preliminar', 'Juicio', 'Apelación', 'Conciliación']),
            'descripcion' => $this->faker->sentence(),
            'lugar' => $this->faker->address(),
            'estado' => $this->faker->randomElement(['Programada', 'Realizada', 'Cancelada', 'Pendiente']),
            'responsable' => $this->faker->name(),
            'notas_internas' => $this->faker->paragraph(),
            'resultado' => $this->faker->optional()->sentence(),
        ];
    }
}