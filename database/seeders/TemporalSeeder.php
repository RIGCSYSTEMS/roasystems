<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TemporalExpediente;

class TemporalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $TemporalExpediente = new TemporalExpediente();

        $TemporalExpediente->date = '2024-10-21';
        $TemporalExpediente->fecha_de_recepcion = '2024-10-21';
        $TemporalExpediente->observaciones = 'Observaciones 1';
        $TemporalExpediente->tiempo = '20 minutos';
        $TemporalExpediente->persona_responsable = 'Andrea';

        $TemporalExpediente->save();

        TemporalExpediente::factory(1000)->create();
    }
}
