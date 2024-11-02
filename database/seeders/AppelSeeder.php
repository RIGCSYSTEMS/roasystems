<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AppelExpediente;

class AppelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $AppelExpediente = new AppelExpediente();

        $AppelExpediente->date = '2024-10-21';
        $AppelExpediente->fecha_de_recepcion = '2024-10-21';
        $AppelExpediente->observaciones = 'Observaciones 1';
        $AppelExpediente->tiempo = '20 minutos';
        $AppelExpediente->persona_responsable = 'Andrea';

        $AppelExpediente->save();

        AppelExpediente::factory(1000)->create();
    }
}
