<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       
        $this->call([

            userSeeder::class,
            TiposDocumentosSeeder::class,
            TiposExpedientesSeeder::class,
            clientSeeder::class,
            ExpedienteSeeder::class,         
           
            // AgendaSeeder::class,
            AudienciasSeeder::class,
            
            // documentosseeder::class,
            // EtapasExpedientesSeeder::class,
            HonorariosSeeder::class,
            BitacorasSeeder::class,
          
            // followSeeder::class,
            // callSeeder::class,
            // residentSeeder::class,
            // ApadrinamientoSeeder::class,
            // HumanitariaSeeder::class,
            // AsiloSeeder::class,
            // TemporalSeeder::class,
            // ErarSeeder::class,
            // PermanenteSeeder::class,
            // documentosseeder::class,
        ]);    
       
       
       
        // // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
