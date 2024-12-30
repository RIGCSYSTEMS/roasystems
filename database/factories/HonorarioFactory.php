<?php

namespace Database\Factories;

use App\Models\Honorario;
use App\Models\Expediente;
use Illuminate\Database\Eloquent\Factories\Factory;

class HonorarioFactory extends Factory
{
    protected $model = Honorario::class;

    public function definition(): array
    {
        return [
            'expediente_id' => Expediente::inRandomOrder()->first()?->id ?? Expediente::factory(),
            'factura' => $this->faker->unique()->numerify('A######'),
            'fecha' => $this->faker->date(),
            'monto' => $this->faker->randomFloat(2, 100, 5000),
            'estado' => $this->faker->randomElement(['pendiente', 'pagado', 'cancelado']),
            'metodo_de_pago' => $this->faker->randomElement(['efectivo', 'tarjeta', 'transferencia']),
            'descripcion' => $this->faker->sentence(),
        ];
    }
}
