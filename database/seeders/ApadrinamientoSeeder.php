<?php

namespace Database\Seeders;
use App\Models\ApadrinamientoExpediente;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApadrinamientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ApadrinamientoExpediente= new ApadrinamientoExpediente();

        $ApadrinamientoExpediente->date = '2024-10-21';
        $ApadrinamientoExpediente->fecha_de_recepcion = '2024-10-21';
        $ApadrinamientoExpediente->observaciones = 'Observaciones 1';
        $ApadrinamientoExpediente->tiempo = '20 minutos';
        $ApadrinamientoExpediente->persona_responsable = 'Andrea';

        $ApadrinamientoExpediente->save();

        ApadrinamientoExpediente::factory(1000)->create();
    }
}
