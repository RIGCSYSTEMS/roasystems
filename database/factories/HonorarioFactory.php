<?php

namespace Database\Factories;

use App\Models\Honorario;
use App\Models\Expediente;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class HonorarioFactory extends Factory
{
    protected $model = Honorario::class;

    public function definition()
    {
        return [
            'expediente_id' => Expediente::factory(),
            'usuario_id' => User::factory(),
            'monto_inicial' => $this->faker->randomFloat(2, 1000, 10000),
            'fecha_apertura' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
        // $montoTotalExpediente = $this->faker->numberBetween(1000, 5000);
        // $montoAdicional = $this->faker->numberBetween(0, 1000);
        // $montoTotalAPagar = $montoTotalExpediente + $montoAdicional;

        // return [
        //     'expediente_id' => Expediente::factory(),
        //     'monto_total_expediente' => $montoTotalExpediente,
        //     'monto_adicional' => $montoAdicional,
        //     'monto_total_a_pagar' => $montoTotalAPagar,
        //     'total_abonos' => 0,
        //     'saldo_pendiente' => $montoTotalAPagar,
        //     'estado' => $this->faker->randomElement(['pendiente', 'pagado', 'cancelado']),
        //     'descripcion' => $this->faker->sentence(),
        // ];
    }
}