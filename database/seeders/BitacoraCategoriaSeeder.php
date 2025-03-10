<?php

namespace Database\Seeders;

use App\Models\BitacoraCategoria;
use Illuminate\Database\Seeder;

class BitacoraCategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['codigo' => 'llamada', 'nombre' => 'Llamada telefónica', 'descripcion' => 'Registro de llamadas telefónicas con clientes o terceros'],
            ['codigo' => 'correo', 'nombre' => 'Correo electrónico', 'descripcion' => 'Registro de correos electrónicos enviados o recibidos'],
            ['codigo' => 'reunion', 'nombre' => 'Reunión con cliente', 'descripcion' => 'Registro de reuniones presenciales o virtuales con clientes'],
            ['codigo' => 'documento', 'nombre' => 'Preparación de documento', 'descripcion' => 'Registro de preparación o revisión de documentos'],
            ['codigo' => 'audiencia', 'nombre' => 'Audiencia', 'descripcion' => 'Registro de audiencias o comparecencias'],
            ['codigo' => 'investigacion', 'nombre' => 'Investigación', 'descripcion' => 'Registro de investigaciones o búsquedas de información'],
            ['codigo' => 'pago', 'nombre' => 'Registro de pago', 'descripcion' => 'Registro de pagos realizados o recibidos'],
            ['codigo' => 'otro', 'nombre' => 'Otro', 'descripcion' => 'Otras actividades no categorizadas'],
        ];

        foreach ($categorias as $categoria) {
            BitacoraCategoria::create([
                'codigo' => $categoria['codigo'],
                'nombre' => $categoria['nombre'],
                'descripcion' => $categoria['descripcion'],
                'activo' => true,
            ]);
        }
    }
}

