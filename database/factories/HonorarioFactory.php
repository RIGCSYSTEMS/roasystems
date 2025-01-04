<?php

namespace Database\Factories;

use App\Models\Honorario;
use App\Models\Expediente;
use Illuminate\Database\Eloquent\Factories\Factory;

class HonorarioFactory extends Factory
{
    protected $model = Honorario::class;

    public function definition()
    {
        $montoTotalExpediente = $this->faker->numberBetween(1000, 5000);
        $montoAdicional = $this->faker->numberBetween(0, 1000);
        $montoTotalAPagar = $montoTotalExpediente + $montoAdicional;

        return [
            'expediente_id' => Expediente::factory(),
            'monto_total_expediente' => $montoTotalExpediente,
            'monto_adicional' => $montoAdicional,
            'monto_total_a_pagar' => $montoTotalAPagar,
            'total_abonos' => 0,
            'saldo_pendiente' => $montoTotalAPagar,
            'estado' => $this->faker->randomElement(['pendiente', 'pagado', 'cancelado']),
            'descripcion' => $this->faker->sentence(),
        ];
    }
}