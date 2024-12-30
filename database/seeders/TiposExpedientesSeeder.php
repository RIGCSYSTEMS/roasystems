<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposExpedientesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipos_expedientes')->insert([
            ['nombre' => 'ASILO', 'descripcion' => 'ASILO', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'APPEL', 'descripcion' => 'APPEL', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'RESIDENCIA PERMANENTE', 'descripcion' => 'RESIDENCIA PERMANENTE', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'ERAR', 'descripcion' => 'ERAR', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'APADRINAMIENTO', 'descripcion' => 'APADRINAMIENTO', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'HUMANITARIA', 'descripcion' => 'HUMANITARIA', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'RESIDENCIA TEMPORAL', 'descripcion' => 'RESIDENCIA TEMPORAL', 'created_at' => now(), 'updated_at' => now()],
            ]);
    }
}
