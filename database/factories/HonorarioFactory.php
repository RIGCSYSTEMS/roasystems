<?php

namespace Database\Factories;

use App\Models\Honorario;
use App\Models\Expediente;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HonorarioFactory extends Factory
{
    protected $model = Honorario::class;

    public function definition()
    {
        $montoTotalExpediente = $this->faker->randomFloat(2, 1000, 10000);
        $montoAdicional = $this->faker->randomFloat(2, 0, 2000);
        $montoTotalAPagar = $montoTotalExpediente + $montoAdicional;
        $totalAbonos = $this->faker->randomFloat(2, 0, $montoTotalAPagar);
        $saldoPendiente = $montoTotalAPagar - $totalAbonos;
        
        // Determinar el estado basado en el saldo pendiente
        $estado = 'pendiente';
        if ($saldoPendiente <= 0) {
            $estado = 'pagado';
        } elseif ($this->faker->boolean(10)) { // 10% de probabilidad de estar cancelado
            $estado = 'cancelado';
        }

        return [
            'expediente_id' => Expediente::factory(),
            'monto_total_expediente' => $montoTotalExpediente,
            'monto_adicional' => $montoAdicional,
            'monto_total_a_pagar' => $montoTotalAPagar,
            'total_abonos' => $totalAbonos,
            'saldo_pendiente' => $saldoPendiente,
            'estado' => $estado,
            'descripcion' => $this->faker->optional(0.7)->paragraph(1), // 70% de probabilidad de tener descripción
            'usuario_id' => User::factory(),
        ];
    }

    /**
     * Estado pagado
     */
    public function pagado()
    {
        return $this->state(function (array $attributes) {
            $montoTotalAPagar = $attributes['monto_total_expediente'] + $attributes['monto_adicional'];
            return [
                'total_abonos' => $montoTotalAPagar,
                'saldo_pendiente' => 0,
                'estado' => 'pagado',
            ];
        });
    }

    /**
     * Estado cancelado
     */
    public function cancelado()
    {
        return $this->state(function (array $attributes) {
            return [
                'estado' => 'cancelado',
            ];
        });
    }
}