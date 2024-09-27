<?php

namespace Database\Seeders;

use App\Models\resident;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class residentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        $resident = new resident();

        $resident->id_cliente = '1';
        $resident->fecha_de_demanda = '2021-10-10';
        $resident->tipo_de_demanda = 'Demanda 1';
        $resident->usuario = 'usuario 1';
        $resident->password = 'password 1';
        $resident->preguntas_respuestas = 'preguntas_respuestas 1';
        $resident->despacho = 'despacho 1';
        $resident->abogado = 'carolina';
        $resident->confirmacion = 'confirmacion 1';
        $resident->notas = 'notas 1';
        
        $resident->save();



        resident::factory(100)->create();

    }
}


// $table->id();
// $table->string('id_cliente');
// $table->string('fecha_de_demanda');
// $table->string('tipo_de_demanda');
// $table->string('usuario');
// $table->string('password');
// $table->longText('preguntas_respuestas');
// $table->string('despacho');
// $table->string('abogado');
// $table->string('confirmacion');
// $table->longText('notas');
// $table->timestamps();