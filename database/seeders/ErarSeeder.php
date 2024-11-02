<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ErarExpediente;

class ErarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ErarExpediente = new ErarExpediente();

        $ErarExpediente->date = '2024-10-21';
        $ErarExpediente->fecha_de_recepcion = '2024-10-21';
        $ErarExpediente->observaciones = 'Observaciones 1';
        $ErarExpediente->tiempo = '20 minutos';
        $ErarExpediente->persona_responsable = 'Andrea';

        $ErarExpediente->save();

        ErarExpediente::factory(1000)->create();
    }
}
