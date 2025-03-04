<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Honorario;
use App\Models\Abono;
use App\Models\User;

class AbonosSeeder extends Seeder
{
    public function run()
    {
        $honorarios = Honorario::all();
        $usuarios = User::all();

        if ($usuarios->isEmpty()) {
            $usuarios = User::factory()->count(5)->create();
        }

        foreach ($honorarios as $honorario) {
            $saldoPendiente = $honorario->saldo_pendiente;
            $numAbonos = rand(1, 5);
            $totalAbonos = 0;

            for ($i = 0; $i < $numAbonos && $saldoPendiente > 0; $i++) {
                $montoAbono = $saldoPendiente > 100 ? rand(100, min(1000, $saldoPendiente)) : $saldoPendiente;
                $gstRate = 5;
                $qstRate = 9.975;
                $impuestos = ($montoAbono * $gstRate / 100) + ($montoAbono * $qstRate / 100);

                Abono::create([
                    'honorario_id' => $honorario->id,
                    'monto' => $montoAbono,
                    'fecha' => now()->subDays(rand(0, 365)),
                    'gst_rate' => $gstRate,
                    'qst_rate' => $qstRate,
                    'impuestos' => $impuestos,
                    'usuario_id' => $usuarios->random()->id,
                ]);

                $saldoPendiente -= $montoAbono;
                $totalAbonos += $montoAbono;
            }

            // Actualizar el honorario con los nuevos totales
            $honorario->total_abonos = $totalAbonos;
            $honorario->saldo_pendiente = $honorario->monto_total_a_pagar - $totalAbonos;
            $honorario->estado = $honorario->saldo_pendiente <= 0 ? 'pagado' : 'pendiente';
            $honorario->save();
        }
    }
}