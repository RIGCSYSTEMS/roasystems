<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PermanenteExpediente;

class PermanenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $PermanenteExpediente = new PermanenteExpediente();

        $PermanenteExpediente->date = '2024-10-21';
        $PermanenteExpediente->fecha_de_recepcion = '2024-10-21';
        $PermanenteExpediente->observaciones = 'Observaciones 1';
        $PermanenteExpediente->tiempo = '20 minutos';
        $PermanenteExpediente->persona_responsable = 'Andrea';

        $PermanenteExpediente->save();

        PermanenteExpediente::factory(1000)->create();
    }
}
