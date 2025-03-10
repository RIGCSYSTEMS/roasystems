<?php

namespace Database\Seeders;

use App\Models\Bitacora;
use App\Models\BitacoraActualizacion;
use App\Models\BitacoraCategoria;
use App\Models\BitacoraHistorialEstado;
use App\Models\User;
use App\Models\Expediente;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BitacorasSeeder extends Seeder
{
    public function run(): void
    {
        // Desactivar verificación de claves foráneas temporalmente
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        try {
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

            // Establecer fecha base para todas las bitácoras (6 meses atrás)
            $fechaBase = Carbon::now()->subMonths(6);
            
            // Para cada expediente, crear varias bitácoras
            foreach ($expedientes as $index => $expediente) {
                // Crear entre 3 y 10 bitácoras por expediente
                $numBitacoras = rand(3, 10);
                
                for ($i = 0; $i < $numBitacoras; $i++) {
                    // Avanzar la fecha base de manera incremental (entre 1 y 3 días)
                    $fechaCreacion = $fechaBase->copy()->addDays(($index * $numBitacoras + $i) * 2);
                    
                    // Si la fecha calculada es futura, usar la fecha actual menos 1 día
                    if ($fechaCreacion->isFuture()) {
                        $fechaCreacion = Carbon::now()->subDay();
                    }
                    
                    // Determinar el tipo de bitácora (70% normal, 30% seguimiento)
                    $tipo = $this->faker()->boolean(70) ? 'normal' : 'seguimiento';
                    
                    // Seleccionar una categoría aleatoria
                    $categoria = $categorias->random();
                    
                    // Crear datos base para la bitácora
                    $bitacoraData = [
                        'expediente_id' => $expediente->id,
                        'user_id' => $usuarios->random()->id,
                        'categoria_id' => $categoria->id,
                        'tipo' => $tipo,
                        'titulo' => $tipo === 'normal' 
                            ? $categoria->nombre 
                            : $this->faker()->sentence(),
                        'descripcion' => $this->faker()->paragraph(),
                        'tiempo_dedicado' => $this->faker()->numberBetween(15, 180),
                        'created_at' => $fechaCreacion,
                        'updated_at' => $fechaCreacion
                    ];
                    
                    // Configurar datos específicos según el tipo
                    if ($tipo === 'normal') {
                        $bitacoraData['estado'] = 'completado';
                        $bitacoraData['fecha_completado'] = $fechaCreacion;
                    } else {
                        // Para bitácoras de seguimiento
                        
                        // Determinar si la bitácora está completada (50% de probabilidad)
                        $completada = $this->faker()->boolean(50);
                        
                        if ($completada) {
                            // Si está completada, la fecha de completado debe ser pasada
                            $fechaCompletado = $fechaCreacion->copy()->addDays(rand(1, 5));
                            
                            // Asegurar que la fecha de completado no sea futura
                            if ($fechaCompletado->isFuture()) {
                                $fechaCompletado = Carbon::now()->subHour();
                            }
                            
                            $bitacoraData['estado'] = 'completado';
                            $bitacoraData['fecha_completado'] = $fechaCompletado;
                            $bitacoraData['updated_at'] = $fechaCompletado;
                            
                            // 20% de probabilidad de reactivación
                            if ($this->faker()->boolean(20)) {
                                $fechaReactivacion = $fechaCompletado->copy()->addDays(rand(1, 3));
                                
                                // Asegurar que la fecha de reactivación no sea futura
                                if ($fechaReactivacion->isFuture()) {
                                    $fechaReactivacion = Carbon::now()->subMinutes(30);
                                }
                                
                                $bitacoraData['estado'] = 'en_proceso';
                                $bitacoraData['fecha_completado'] = null;
                                $bitacoraData['fecha_reactivacion'] = $fechaReactivacion;
                                $bitacoraData['updated_at'] = $fechaReactivacion;
                            }
                            
                            // Si está completada, la fecha límite debe ser pasada
                            $fechaLimite = $fechaCreacion->copy()->addDays(rand(10, 20));
                            if ($fechaLimite->isAfter($fechaCompletado)) {
                                // 70% de probabilidad de que se complete antes de la fecha límite
                                if ($this->faker()->boolean(70)) {
                                    $fechaLimite = $fechaCompletado->copy()->addDays(rand(5, 15));
                                } else {
                                    $fechaLimite = $fechaCompletado->copy()->subDays(rand(1, 5));
                                }
                            }
                        } else {
                            // Si no está completada, puede tener fecha límite futura
                            $bitacoraData['estado'] = $this->faker()->randomElement(['en_proceso', 'pendiente']);
                            
                            // Decidir si la fecha límite es pasada o futura
                            if ($this->faker()->boolean(40)) {
                                // 40% de probabilidad de fecha límite pasada (vencida)
                                $fechaLimite = $fechaCreacion->copy()->addDays(rand(1, 10));
                                if ($fechaLimite->isFuture()) {
                                    $fechaLimite = Carbon::now()->subDays(rand(1, 5));
                                }
                            } else {
                                // 60% de probabilidad de fecha límite futura
                                $fechaLimite = Carbon::now()->addDays(rand(5, 60));
                            }
                        }
                        
                        $bitacoraData['fecha_limite'] = $fechaLimite->format('Y-m-d');
                        $bitacoraData['prioridad_fecha'] = $this->faker()->randomElement(['normal', 'importante', 'critica']);
                    }
                    
                    // Crear la bitácora
                    $bitacora = Bitacora::create($bitacoraData);
                    
                    // Crear historial de estados
                    $this->crearHistorialEstados($bitacora, $usuarios);
                    
                    // Si es de seguimiento, crear actualizaciones
                    if ($tipo === 'seguimiento') {
                        $this->crearActualizaciones($bitacora, $usuarios, $categorias);
                    }
                }
            }
        } finally {
            // Reactivar verificación de claves foráneas
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }
    
    private function crearHistorialEstados(Bitacora $bitacora, $usuarios): void
    {
        // Crear registro de creación
        BitacoraHistorialEstado::create([
            'bitacora_id' => $bitacora->id,
            'user_id' => $usuarios->random()->id,
            'accion' => 'Creación de bitácora',
            'fecha' => $bitacora->created_at,
            'created_at' => $bitacora->created_at,
            'updated_at' => $bitacora->created_at
        ]);
        
        // Si está completada, crear registro de completado
        if ($bitacora->estado === 'completado' && $bitacora->fecha_completado) {
            BitacoraHistorialEstado::create([
                'bitacora_id' => $bitacora->id,
                'user_id' => $usuarios->random()->id,
                'accion' => 'Marcada como completada',
                'fecha' => $bitacora->fecha_completado,
                'created_at' => $bitacora->fecha_completado,
                'updated_at' => $bitacora->fecha_completado
            ]);
        }
        
        // Si fue reactivada, crear registro de reactivación
        if ($bitacora->fecha_reactivacion) {
            BitacoraHistorialEstado::create([
                'bitacora_id' => $bitacora->id,
                'user_id' => $usuarios->random()->id,
                'accion' => 'Reactivada',
                'fecha' => $bitacora->fecha_reactivacion,
                'created_at' => $bitacora->fecha_reactivacion,
                'updated_at' => $bitacora->fecha_reactivacion
            ]);
        }
    }
    
    private function crearActualizaciones(Bitacora $bitacora, $usuarios, $categorias): void
    {
        // Crear entre 0 y 5 actualizaciones
        $numActualizaciones = rand(0, 5);
        
        // Fecha base para las actualizaciones (fecha de creación de la bitácora)
        $fechaBase = Carbon::parse($bitacora->created_at);
        
        for ($i = 0; $i < $numActualizaciones; $i++) {
            // Incrementar la fecha base de manera secuencial (1-2 días por actualización)
            $fechaActualizacion = $fechaBase->copy()->addDays($i + 1);
            
            // Si la fecha calculada es futura, salir del bucle
            if ($fechaActualizacion->isFuture()) {
                break;
            }
            
            // Si la bitácora está completada, hay 70% de probabilidad de que sea un comentario
            $esComentario = $bitacora->estado === 'completado' && $this->faker()->boolean(70);
            
            BitacoraActualizacion::create([
                'bitacora_id' => $bitacora->id,
                'user_id' => $usuarios->random()->id,
                'categoria_id' => $categorias->random()->id,
                'descripcion' => $this->faker()->paragraph(),
                'tiempo_dedicado' => $this->faker()->numberBetween(10, 120),
                'estado' => $esComentario ? 'comentario' : ($this->faker()->boolean(60) ? 'completado' : $this->faker()->randomElement(['en_proceso', 'pendiente'])),
                'es_comentario' => $esComentario,
                'created_at' => $fechaActualizacion,
                'updated_at' => $fechaActualizacion
            ]);
        }
    }
    
    private function faker()
    {
        return \Faker\Factory::create('es_ES');
    }
}

