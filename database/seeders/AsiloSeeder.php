<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AsiloExpediente;

class AsiloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $AsiloExpediente = new AsiloExpediente();

        $AsiloExpediente->date = '2024-10-21';
        $AsiloExpediente->fecha_de_recepcion = '2024-10-21';
        $AsiloExpediente->observaciones = 'Observaciones 1';
        $AsiloExpediente->tiempo = '20 minutos';
        $AsiloExpediente->persona_responsable = 'Andrea';

        $AsiloExpediente->save();

        AsiloExpediente::factory(1000)->create();
    }
}
