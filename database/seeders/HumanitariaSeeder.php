<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HumanitariaExpediente;        

class HumanitariaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $HumanitariaExpediente = new HumanitariaExpediente();

        $HumanitariaExpediente->date = '2024-10-21';
        $HumanitariaExpediente->fecha_de_recepcion = '2024-10-21';
        $HumanitariaExpediente->observaciones = 'Observaciones 1';
        $HumanitariaExpediente->tiempo = '20 minutos';
        $HumanitariaExpediente->persona_responsable = 'Andrea';

        $HumanitariaExpediente->save();

        HumanitariaExpediente::factory(1000)->create();
    }
}
