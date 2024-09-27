<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\call;

class callSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $call = new call();

        $call->fecha = '2021-10-10';
        $call->nombre = 'nombre 1';
        $call->telefono = 'telefono 1';
        $call->aboagado = 'aboagado 1';
        $call->motivo = 'motivo 1';
        $call->nota = 'nota 1';
        $call->requiere_accion = 'requiere_accion 1';

        $call->save();

        call::factory(100)->create();
    }
}


// $table->id();
// $table->string('fecha');
// $table->string('nombre');
// $table->string('telefono');
// $table->string('aboagado');
// $table->string('motivo');
// $table->string('nota');
// $table->string('requiere_accion');
// $table->timestamps();