<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposDocumentosExpedientesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipos_documentos_expedientes')->insert([
            ['nombre' => 'IDENTIFICACION', 'descripcion' => 'Documento de identificación oficial', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'PASAPORTE', 'descripcion' => 'Pasaporte válido', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'PERMISO DE TRABAJO', 'descripcion' => 'Permiso de trabajo vigente', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'HOJA MARRON', 'descripcion' => 'Documento de hoja marrón', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'PRUEBAS', 'descripcion' => 'Pruebas del expediente', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'HISTORIA', 'descripcion' => 'Historia del cliente', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'RESIDENCIA PERMANENTE', 'descripcion' => 'Documentos de residencia permanente', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'CAQ', 'descripcion' => 'Certificado de aceptación en Quebec', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'EXTRAS', 'descripcion' => 'Documentos adicionales', 'created_at' => now(), 'updated_at' => now()],
            ]);
    }
}
