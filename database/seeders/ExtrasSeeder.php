<?php

namespace Database\Seeders;

use App\Models\Extra;
use App\Models\Honorario;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExtrasSeeder extends Seeder
{
    public function run()
    {
        $honorarios = Honorario::all();
        $usuarios = User::all();

        if ($usuarios->isEmpty()) {
            $usuarios = User::factory()->count(5)->create();
        }

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

        foreach ($honorarios as $honorario) {
            // Crear entre 0 y 3 extras por honorario
            $numExtras = rand(0, 3);
            $totalExtras = 0;

            for ($i = 0; $i < $numExtras; $i++) {
                $montoExtra = rand(50, 500);
                $gstRate = 5;
                $qstRate = 9.975;
                $impuestos = ($montoExtra * $gstRate / 100) + ($montoExtra * $qstRate / 100);

                Extra::create([
                    'honorario_id' => $honorario->id,
                    'concepto' => $conceptos[array_rand($conceptos)],
                    'monto' => $montoExtra,
                    'fecha' => now()->subDays(rand(0, 365)),
                    'gst_rate' => $gstRate,
                    'qst_rate' => $qstRate,
                    'impuestos' => $impuestos,
                    'usuario_id' => $usuarios->random()->id,
                ]);

                $totalExtras += $montoExtra;
            }

            // Actualizar el honorario con los nuevos montos adicionales
            if ($totalExtras > 0) {
                $honorario->monto_adicional += $totalExtras;
                $honorario->monto_total_a_pagar = $honorario->monto_total_expediente + $honorario->monto_adicional;
                $honorario->saldo_pendiente = $honorario->monto_total_a_pagar - $honorario->total_abonos;
                $honorario->save();
            }
        }
    }
}