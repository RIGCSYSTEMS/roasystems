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
        $client->otros_nombres_de_cliente = 'Rigc1234';
        $client->direccion = 'Calle 123';
        $client->telefono = '12345678';
        $client->email = 'admin@example.com';
        $client->profesion = 'Ingeniero';
        $client->pais = 'Mexico';
        $client->lenguaje = 'Español';
        $client->estatus = 'Activo';
        $client->permiso_de_trabajo = 'Permiso 1';
        $client->iuc = 'IUC 1';
        $client->observaciones = 'Observaciones 1';
        

         $client->save();

        client::factory(1000)->create();

    }
}
