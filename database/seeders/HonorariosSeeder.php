<?php

namespace Database\Seeders;

use App\Models\Honorario;
use App\Models\Expediente;
use Illuminate\Database\Seeder;

class HonorariosSeeder extends Seeder
{
    public function run()
    {
        // Asumimos que ya existen expedientes en la base de datos
        $expedientes = Expediente::all();

        if ($expedientes->isEmpty()) {
            // Si no hay expedientes, creamos algunos para pruebas
            $expedientes = Expediente::factory()->count(10)->create();
        }

        foreach ($expedientes as $expediente) {
            Honorario::factory()->create([
                'expediente_id' => $expediente->id,
            ]);
        }
    }
}