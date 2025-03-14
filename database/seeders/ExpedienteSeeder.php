<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Expediente;
use App\Models\client;
use Illuminate\Support\Facades\DB;

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
        $totalExpedientes = 0;

        foreach ($clients as $client) {
            for ($i = 0; $i < 3; $i++) {
            Expediente::create([
                'client_id' => $client->id,
                'tipo_expediente_id' => $this->getRandomStypesexp(),
                'numero_de_dossier' => $this->generateDossierNumber(),
                'estatus_del_expediente' => $this->getRandomStatus(),
                'prioridad' => $this->getRandompriority(),
                'fecha_de_apertura' => now(),
                'fecha_de_cierre' => now()->addMonths(rand(1, 12)),
                'despacho' => 'Despacho ' . rand(1, 5),
                'abogado' => 'Abogado ' . rand(1, 10),
                'plazo_fda' => now()->addMonths(rand(1, 12)),
                'progreso' => rand(0, 100),
                'boite' => 'Boite ' . rand(1, 5),
                'observaciones' => $this->getRandomObservation(),
            ]);
            $totalExpedientes++;
        }
    }
        $this->command->info('Se han creado ' . $clients->count() . ' expedientes, uno para cada cliente.');
    }

    private function getRandompriority()
    {
        $priorities = ['Urgente', 'Normal'];
        return $priorities[array_rand($priorities)];
    }

    private function getRandomStypesexp()
    {
        // $tipos_expedientes = ['ASILO', 'APPEL','RESIDENCIA PERMANENTE','ERAR','APADRINAMIENTO','HUMANITARIA','RESIDENCIA TEMPORAL'];
        $tipos_expedientes = DB::table('tipos_expedientes')->pluck('id')->toArray();
        return $tipos_expedientes[array_rand($tipos_expedientes)];
        }

    private function getRandomStatus()
    {
        $statuses = ['Abierto', 'Cerrado', 'Cancelado'];
        return $statuses[array_rand($statuses)];
    }

    private function generateDossierNumber()
    {
        return 'DOS-' . strtoupper(substr(md5(microtime()), 0, 8));
    }

    private function getRandomObservation()
{
    $observations = [
        "Revisión pendiente",
        "En proceso",
        "Requiere seguimiento",
        "Documentación incompleta",
        "Caso en espera",
        "Próxima actualización",
        "Consulta programada",
        "Sin novedades",
        "Avance normal",
        "Atención requerida",
        "Expediente activo",
        "En evaluación",
        "Pendiente de respuesta",
        "Seguimiento semanal",
        "Caso estándar",
    ];

    return $observations[array_rand($observations)];
}

}
