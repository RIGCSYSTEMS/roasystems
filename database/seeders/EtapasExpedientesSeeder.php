<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Expediente;

class EtapasExpedientesSeeder extends Seeder
{
    public function run()
    {
    //     DB::table('etapas_expedientes')->insert([
    //         [
    //             'expediente_id' => 1, // Reemplaza con un ID válido de un expediente existente
    //             'nombre' => 'Admisión',
    //             'completada' => false,
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'expediente_id' => 1, // Puedes cambiar al expediente que corresponda
    //             'nombre' => 'Evaluación',
    //             'completada' => false,
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'expediente_id' => 2, // Otro expediente como ejemplo
    //             'nombre' => 'Cierre',
    //             'completada' => false,
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //     ]);
    // }
    $etapas = [
        ['nombre' => 'admision', 'porcentaje' => 10],
        ['nombre' => 'historia', 'porcentaje' => 25],
        ['nombre' => 'pruebas', 'porcentaje' => 50],
        ['nombre' => 'audiencia', 'porcentaje' => 75],
        ['nombre' => 'cierre', 'porcentaje' => 100],
    ];

    Expediente::all()->each(function ($expediente) use ($etapas) {
        foreach ($etapas as $etapa) {
            if ($expediente->etapas()->where('nombre', $etapa['nombre'])->doesntExist()) {
                $expediente->etapas()->create($etapa);
            }
        }
    });
 }
}