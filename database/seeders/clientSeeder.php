<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;

class clientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    
        
        

        $client = new client();

        $client->nombre_de_cliente = 'Ivan';
        $client->familia = [
            ['nombre' => 'diana', 'parentesco' => 'hermana'],
            ['nombre' => 'santiago', 'parentesco' => 'sobrino'],
            ['nombre' => 'camila', 'parentesco' => 'sobrina']
        ];
        $client->fecha_de_nacimiento = '2021-09-26';
        $client->genero = 'Masculino';
        $client->estado_civil = 'Soltero';
        $client->pais = 'Mexico';
        $client->llegada_a_canada = '2020-01-23';
        $client->punto_de_acceso = 'Aeropuerto';
        $client->pasaporte = '12345678';
        $client->estatus = 'Activo';
        $client->direccion = 'Calle 123';
        $client->telefono = '12345678';
        $client->email = 'admin@example.com';
        $client->profesion = 'Ingeniero';
        $client->lenguaje = 'Español';
        $client->permiso_de_trabajo = 'Permiso 1';
        $client->iuc = 'IUC 1';
        $client->observaciones = 'Observaciones 1';
        

         $client->save();

        client::factory(10000)->create();

    }
}
