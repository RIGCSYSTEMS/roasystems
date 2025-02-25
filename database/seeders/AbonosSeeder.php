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
            $saldoPendiente = $honorario->monto_inicial;
            $numAbonos = rand(1, 5);

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
            }
        }
        // $honorarios = Honorario::all();
        // $usuarios = User::all();

        // if ($usuarios->isEmpty()) {
        //     $usuarios = User::factory()->count(5)->create();
        // }

        // foreach ($honorarios as $honorario) {
        //     $saldoPendiente = $honorario->monto_total_a_pagar;
        //     $numAbonos = rand(1, 5);

        //     for ($i = 0; $i < $numAbonos && $saldoPendiente > 0; $i++) {
        //         $montoAbono = $saldoPendiente > 100 ? rand(100, min(1000, $saldoPendiente)) : $saldoPendiente;

        //         $abono = new Abono([
        //             'honorario_id' => $honorario->id,
        //             'fecha' => now()->subDays(rand(0, 365)),
        //             'factura' => 'FAC-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
        //             'monto' => $montoAbono,
        //             'metodo_pago' => ['efectivo', 'tarjeta', 'transferencia'][rand(0, 2)],
        //             'usuario_id' => $usuarios->random()->id,
        //         ]);

        //         $abono->save();

        //         $saldoPendiente -= $montoAbono;
        //         $honorario->total_abonos += $montoAbono;
        //     }

        //     $honorario->saldo_pendiente = $saldoPendiente;
        //     $honorario->estado = $saldoPendiente <= 0 ? 'pagado' : 'pendiente';
        //     $honorario->save();
        // }
    }
}