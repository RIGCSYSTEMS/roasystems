<?php

namespace Database\Seeders;

use App\Models\Bitacora;
use App\Models\BitacoraActualizacion;
use App\Models\BitacoraCategoria;
use App\Models\BitacoraHistorialEstado;
use App\Models\User;
use App\Models\Expediente;
use Illuminate\Database\Seeder;

class BitacorasSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener usuarios y expedientes existentes
        $usuarios = User::all();
        $expedientes = Expediente::all();
        $categorias = BitacoraCategoria::all();

        // Si no hay usuarios, crear algunos
        if ($usuarios->isEmpty()) {
            $usuarios = User::factory()->count(5)->create();
        }

        // Si no hay expedientes, crear algunos
        if ($expedientes->isEmpty()) {
            $expedientes = Expediente::factory()->count(10)->create();
        }

        // Verificar que existan categorías
        if ($categorias->isEmpty()) {
            throw new \RuntimeException('No hay categorías de bitácora. Asegúrate de ejecutar BitacoraCategoriaSeeder primero.');
        }

        // Para cada expediente, crear varias bitácoras
        foreach ($expedientes as $expediente) {
            // Crear entre 3 y 10 bitácoras por expediente
            $numBitacoras = rand(3, 10);
            
            for ($i = 0; $i < $numBitacoras; $i++) {
                // Determinar el tipo de bitácora (70% normal, 30% seguimiento)
                $tipo = $this->faker()->boolean(70) ? 'normal' : 'seguimiento';
                
                // Crear la bitácora
                $bitacora = Bitacora::factory()
                    ->state([
                        'expediente_id' => $expediente->id,
                        'user_id' => $usuarios->random()->id,
                        'categoria_id' => $categorias->random()->id,
                        'tipo' => $tipo,
                    ]);
                
                // Si es normal, marcarla como completada
                if ($tipo === 'normal') {
                    $bitacora = $bitacora->normal();
                } else {
                    $bitacora = $bitacora->seguimiento();
                    
                    // 50% de probabilidad de que esté completada
                    if ($this->faker()->boolean(50)) {
                        $bitacora = $bitacora->completada();
                        
                        // 20% de probabilidad de que haya sido reactivada
                        if ($this->faker()->boolean(20)) {
                            $bitacora = $bitacora->reactivada();
                        }
                    }
                }
                
                // Crear la bitácora en la base de datos
                $bitacora = $bitacora->create();
                
                // Crear historial de estados para la bitácora
                $this->crearHistorialEstados($bitacora, $usuarios);
                
                // Si es de seguimiento, crear actualizaciones
                if ($tipo === 'seguimiento') {
                    $this->crearActualizaciones($bitacora, $usuarios, $categorias);
                }
            }
        }
    }
    
    /**
     * Crear historial de estados para una bitácora
     */
    private function crearHistorialEstados(Bitacora $bitacora, $usuarios): void
    {
        // Siempre crear un registro de creación
        BitacoraHistorialEstado::factory()
            ->creacion()
            ->create([
                'bitacora_id' => $bitacora->id,
                'user_id' => $usuarios->random()->id,
                'fecha' => $bitacora->created_at,
            ]);
        
        // Si está completada, crear un registro de completado
        if ($bitacora->estado === 'completado' && $bitacora->fecha_completado) {
            BitacoraHistorialEstado::factory()
                ->completado()
                ->create([
                    'bitacora_id' => $bitacora->id,
                    'user_id' => $usuarios->random()->id,
                    'fecha' => $bitacora->fecha_completado,
                ]);
        }
        
        // Si fue reactivada, crear un registro de reactivación
        if ($bitacora->fecha_reactivacion) {
            BitacoraHistorialEstado::factory()
                ->reactivacion()
                ->create([
                    'bitacora_id' => $bitacora->id,
                    'user_id' => $usuarios->random()->id,
                    'fecha' => $bitacora->fecha_reactivacion,
                ]);
        }
    }
    
    /**
     * Crear actualizaciones para una bitácora de seguimiento
     */
    private function crearActualizaciones(Bitacora $bitacora, $usuarios, $categorias): void
    {
        // Crear entre 0 y 5 actualizaciones
        $numActualizaciones = rand(0, 5);
        
        for ($i = 0; $i < $numActualizaciones; $i++) {
            // Si la bitácora está completada, hay 70% de probabilidad de que sea un comentario
            $esComentario = $bitacora->estado === 'completado' && $this->faker()->boolean(70);
            
            $actualizacion = BitacoraActualizacion::factory();
            
            if ($esComentario) {
                $actualizacion = $actualizacion->comentario();
            } else {
                // Si no es comentario, 60% de probabilidad de que esté completada
                if ($this->faker()->boolean(60)) {
                    $actualizacion = $actualizacion->completada();
                }
            }
            
            $actualizacion->create([
                'bitacora_id' => $bitacora->id,
                'user_id' => $usuarios->random()->id,
                'categoria_id' => $categorias->random()->id,
                'created_at' => $this->faker()->dateTimeBetween($bitacora->created_at, 'now'),
            ]);
        }
    }
    
    /**
     * Get a new Faker instance
     */
    private function faker()
    {
        return \Faker\Factory::create('es_ES');
    }
}

