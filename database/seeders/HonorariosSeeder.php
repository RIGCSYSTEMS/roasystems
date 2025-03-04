<?php

namespace Database\Seeders;

use App\Models\Honorario;
use App\Models\Expediente;
use App\Models\User;
use Illuminate\Database\Seeder;

class HonorariosSeeder extends Seeder
{
    public function run()
    {
        // Asumimos que ya existen expedientes en la base de datos
        $expedientes = Expediente::all();
        $usuarios = User::whereIn('role', ['ADMIN', 'DIRECTOR', 'ABOGADO'])->get();

        if ($expedientes->isEmpty()) {
            // Si no hay expedientes, creamos algunos para pruebas
            $expedientes = Expediente::factory()->count(10)->create();
        }
        
        if ($usuarios->isEmpty()) {
            // Si no hay usuarios, creamos algunos para pruebas
            $usuarios = User::all();
            
            if ($usuarios->isEmpty()) {
                $usuarios = User::factory()->count(5)->create();
            }
        }

        foreach ($expedientes as $expediente) {
            // Verificar si ya tiene honorarios
            if ($expediente->honorarios()->count() > 0) {
                continue; // Saltar si ya tiene honorarios
            }
            
            $montoTotalExpediente = rand(3000, 10000);
            
            Honorario::factory()->create([
                'expediente_id' => $expediente->id,
                'usuario_id' => $usuarios->random()->id,
                'monto_total_expediente' => $montoTotalExpediente,
                'monto_adicional' => 0,
                'monto_total_a_pagar' => $montoTotalExpediente,
                'total_abonos' => 0,
                'saldo_pendiente' => $montoTotalExpediente,
                'estado' => 'pendiente',
            ]);
        }
    }
}