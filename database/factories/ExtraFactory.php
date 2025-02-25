<?php

namespace Database\Factories;

use App\Models\Extra;
use App\Models\Honorario;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExtraFactory extends Factory
{
    protected $model = Extra::class;

    public function definition()
    {
        $monto = $this->faker->randomFloat(2, 50, 500);
        $gst_rate = 5;
        $qst_rate = 9.975;
        $impuestos = ($monto * $gst_rate / 100) + ($monto * $qst_rate / 100);

        $conceptos = [
            'Traducción de documentos',
            'Certificación notarial',
            'Gastos de envío',
            'Copias certificadas',
            'Trámites adicionales',
            'Servicios de interpretación',
            'Asesoría adicional',
            'Gestión especial'
        ];

        return [
            'honorario_id' => Honorario::factory(),
            'concepto' => $this->faker->randomElement($conceptos),
            'monto' => $monto,
            'gst_rate' => $gst_rate,
            'qst_rate' => $qst_rate,
            'impuestos' => $impuestos,
            'fecha' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}