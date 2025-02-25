<?php

namespace Database\Seeders;

use App\Models\Honorario;
use App\Models\Expediente;
use Illuminate\Database\Seeder;
use App\Models\User;

class HonorariosSeeder extends Seeder
{
    public function run()
    {
        // Asumimos que ya existen expedientes en la base de datos
        $expedientes = Expediente::all();
        $usuarios = User::all();

        if ($expedientes->isEmpty()) {
            // Si no hay expedientes, creamos algunos para pruebas
            $expedientes = Expediente::factory()->count(10)->create();
        }
        if ($usuarios->isEmpty()) {
            // Si no hay usuarios, creamos algunos para pruebas
            $usuarios = User::factory()->count(5)->create();
        }

        foreach ($expedientes as $expediente) {
            Honorario::factory()->create([
                'usuario_id' => $usuarios->random()->id,
                'expediente_id' => $expediente->id,
                'monto_inicial' => rand(3000, 10000),
                'fecha_apertura' => now()->subDays(rand(0, 365)),
            ]);
        }
    }
}