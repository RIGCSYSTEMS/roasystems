<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Agenda;

class AgendaSeeder extends Seeder
{
    public function run(): void
    {
        Agenda::factory(30)->create(); // Crea 30 registros de prueba
    }
}
