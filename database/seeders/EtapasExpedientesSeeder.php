<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EtapasExpedientesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Datos de ejemplo para las etapas de expedientes
        $etapas = [
            ['nombre' => 'Admisión', 'descripcion' => 'Primera etapa del expediente, incluye la revisión de documentos iniciales.'],
            ['nombre' => 'Historia', 'descripcion' => 'Etapa para registrar y analizar la historia del cliente.'],
            ['nombre' => 'Pruebas', 'descripcion' => 'Etapa para cargar y analizar las pruebas presentadas por el cliente.'],
            ['nombre' => 'Audiencia', 'descripcion' => 'Etapa final que incluye la preparación para la audiencia.'],
        ];

        // Insertar las etapas en la tabla
        foreach ($etapas as $etapa) {
            DB::table('etapas_expedientes')->insert($etapa);
        }
    }
}
