<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bitacora;

class BitacoraSeeder extends Seeder
{
    public function run(): void
    {
        Bitacora::factory(50)->create(); // Crea 50 registros de prueba
    }
}
