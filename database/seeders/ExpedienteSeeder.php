<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Expediente;
use App\Models\client;

class ExpedienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los clientes
        $clients = Client::all();

        if ($clients->isEmpty()) {
            $this->command->info('No hay clientes en la base de datos. No se crearán expedientes.');
            return;
        }

        foreach ($clients as $client) {
            Expediente::create([
                'client_id' => $client->id,
                'fecha_de_apertura' => now(),
                'fecha_de_cierre' => now()->addMonths(rand(1, 12)),
                'estatus_del_expediente' => $this->getRandomStatus(),
                'numero_de_dossier' => $this->generateDossierNumber(),
                'despacho' => 'Despacho ' . rand(1, 5),
                'abogado' => 'Abogado ' . rand(1, 10),
                'honorarios' => rand(1000, 10000),
                'tipo' => $this->getRandomType(),
            ]);
        }

        $this->command->info('Se han creado ' . $clients->count() . ' expedientes, uno para cada cliente.');
    }

    private function getRandomStatus()
    {
        $statuses = ['Activo', 'En proceso', 'Cerrado', 'Pendiente'];
        return $statuses[array_rand($statuses)];
    }

    private function generateDossierNumber()
    {
        return 'DOS-' . strtoupper(substr(md5(microtime()), 0, 8));
    }

    private function getRandomType()
    {
        $types = ['asilo', 'appel', 'permanente', 'erar', 'apadrinamiento', 'humanitaria', 'temporal'];
        return $types[array_rand($types)];
    }
    
}
