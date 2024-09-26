<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\client;

class clientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    
        
        client::factory(10)->create();

        // $client = new client();

        // $client->nombre_de_cliente = 'Ivan';
        // $client->otros_nombres_de_cliente = 'Rigc1234';
        // $client->direccion = 'Calle 123';
        // $client->telefono = '12345678';
        // $client->email = 'admin@example.com';
        // $client->profesion = 'Ingeniero';
        // $client->pais = 'Mexico';
        // $client->despacho = 'Despacho 1';
        // $client->tipo_de_expediente = 'Expediente 1';
        // $client->lenguaje = 'Español';
        // $client->honorarios = 'Honorarios 1';
        // $client->fecha_de_apertura = '2024-09-26';
        // $client->estatus = 'Activo';
        // $client->observaciones = 'Observaciones 1';
        // $client->numero_de_expediente = 'Expediente 1';
        // $client->permiso_de_trabajo = 'Permiso 1';
        // $client->IUC = 'IUC 1';
        // $client->ubicacion_del_despacho = 'Despacho 1';
        // $client->fecha_de_cierre = '2024-09-26';
        // $client->cierre_del_numero_de_caja = 'Caja 1';
 
        //  $client->save();

    }
}
