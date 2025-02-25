<?php

namespace Database\Factories;

use App\Models\Abono;
use App\Models\Honorario;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AbonoFactory extends Factory
{
    protected $model = Abono::class;

    public function definition()
    {
        $monto = $this->faker->randomFloat(2, 100, 2000);
        $gst_rate = 5;
        $qst_rate = 9.975;
        $impuestos = ($monto * $gst_rate / 100) + ($monto * $qst_rate / 100);

        return [
            'honorario_id' => Honorario::factory(),
            'monto' => $monto,
            'gst_rate' => $gst_rate,
            'qst_rate' => $qst_rate,
            'impuestos' => $impuestos,
            'fecha' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
        // return [
        //     'honorario_id' => Honorario::factory(),
        //     'fecha' => $this->faker->dateTimeBetween('-1 year', 'now'),
        //     'factura' => $this->faker->bothify('FAC-####'),
        //     'monto' => $this->faker->numberBetween(100, 1000),
        //     'metodo_pago' => $this->faker->randomElement(['efectivo', 'tarjeta', 'transferencia']),
        //     'usuario_id' => User::factory(),
        // ];
    }
}