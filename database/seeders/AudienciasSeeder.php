<?php

namespace Database\Seeders;

use App\Models\Audiencia;
use App\Models\Expediente;
use Illuminate\Database\Seeder;

class AudienciasSeeder extends Seeder
{
    public function run()
    {
        $expedientes = Expediente::all();

        if ($expedientes->isEmpty()) {
            $expedientes = Expediente::factory()->count(10)->create();
        }

        foreach ($expedientes as $expediente) {
            Audiencia::factory()->count(rand(1, 5))->create([
                'expediente_id' => $expediente->id,
            ]);
        }
    }
}