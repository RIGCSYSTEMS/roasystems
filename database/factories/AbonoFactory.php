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
        return [
            'honorario_id' => Honorario::factory(),
            'fecha' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'factura' => $this->faker->bothify('FAC-####'),
            'monto' => $this->faker->numberBetween(100, 1000),
            'metodo_pago' => $this->faker->randomElement(['efectivo', 'tarjeta', 'transferencia']),
            'usuario_id' => User::factory(),
        ];
    }
}