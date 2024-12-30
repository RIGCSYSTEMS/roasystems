<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Honorario;

class HonorarioSeeder extends Seeder
{
    public function run(): void
    {
        Honorario::factory(20)->create(); // Crea 20 registros de prueba
    }
}
