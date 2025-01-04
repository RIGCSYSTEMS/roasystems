<?php

namespace Database\Seeders;

use App\Models\Bitacora;
use App\Models\User;
use App\Models\Expediente;
use Illuminate\Database\Seeder;

class BitacorasSeeder extends Seeder
{
    public function run()
    {
        $usuarios = User::all();
        $expedientes = Expediente::all();

        if ($usuarios->isEmpty()) {
            $usuarios = User::factory()->count(5)->create();
        }

        if ($expedientes->isEmpty()) {
            $expedientes = Expediente::factory()->count(10)->create();
        }

        foreach ($expedientes as $expediente) {
            $numEntradas = rand(3, 10);
            for ($i = 0; $i < $numEntradas; $i++) {
                Bitacora::factory()->create([
                    'usuario_id' => $usuarios->random()->id,
                    'expediente_id' => $expediente->id,
                ]);
            }
        }
    }
}