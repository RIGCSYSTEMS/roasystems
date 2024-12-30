<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EtapasExpedientesSeeder extends Seeder
{
    public function run()
    {
        DB::table('etapas_expedientes')->insert([
            [
                'expediente_id' => 1, // Reemplaza con un ID válido de un expediente existente
                'nombre' => 'Admisión',
                'completada' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'expediente_id' => 1, // Puedes cambiar al expediente que corresponda
                'nombre' => 'Evaluación',
                'completada' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'expediente_id' => 2, // Otro expediente como ejemplo
                'nombre' => 'Cierre',
                'completada' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}