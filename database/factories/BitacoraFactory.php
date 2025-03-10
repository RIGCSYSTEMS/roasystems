<?php

namespace Database\Factories;

use App\Models\Bitacora;
use App\Models\BitacoraCategoria;
use App\Models\User;
use App\Models\Expediente;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class BitacoraFactory extends Factory
{
    protected $model = Bitacora::class;

    public function definition(): array
    {
        // Usar Carbon para manejar fechas de manera consistente
        $now = Carbon::now('UTC');
        $sixMonthsAgo = $now->copy()->subMonths(6);
        
        // Fecha de creación entre hace 6 meses y hace 1 día
        $fechaCreacion = Carbon::createFromTimestamp(
            mt_rand($sixMonthsAgo->timestamp, $now->copy()->subDay()->timestamp)
        )->setTimezone('UTC');
        
        // Fecha de actualización entre la creación y hace 1 hora
        $fechaActualizacion = Carbon::createFromTimestamp(
            mt_rand($fechaCreacion->timestamp, $now->copy()->subHour()->timestamp)
        )->setTimezone('UTC');
        
        $tipo = $this->faker->randomElement(['normal', 'seguimiento']);
        $estado = $this->faker->randomElement(['completado', 'en_proceso', 'pendiente']);
        
        // Fecha de completado debe ser entre la creación y la actualización
        $fechaCompletado = null;
        if ($estado === 'completado') {
            $fechaCompletado = Carbon::createFromTimestamp(
                mt_rand($fechaCreacion->timestamp, $fechaActualizacion->timestamp)
            )->setTimezone('UTC');
        }
        
        // Fecha de reactivación debe ser entre el completado y la actualización
        $fechaReactivacion = null;
        if ($estado !== 'completado' && $this->faker->boolean(20) && $fechaCompletado) {
            $fechaReactivacion = Carbon::createFromTimestamp(
                mt_rand($fechaCompletado->timestamp, $fechaActualizacion->timestamp)
            )->setTimezone('UTC');
        }
        
        // Fecha límite depende del estado
        $fechaLimite = null;
        $prioridadFecha = null;
        if ($tipo === 'seguimiento') {
            if ($estado === 'completado' && $fechaCompletado) {
                // Si está completada, la fecha límite debe ser anterior a la fecha de completado
                $fechaLimite = Carbon::createFromTimestamp(
                    mt_rand($fechaCreacion->timestamp, $fechaCompletado->timestamp)
                )->setTimezone('UTC');
            } else {
                // Si no está completada, la fecha límite puede ser futura
                $fechaLimite = Carbon::createFromTimestamp(
                    mt_rand($now->timestamp, $now->copy()->addMonths(3)->timestamp)
                )->setTimezone('UTC');
            }
            $fechaLimite = $fechaLimite->format('Y-m-d');
            $prioridadFecha = $this->faker->randomElement(['normal', 'importante', 'critica']);
        }

        return [
            'expediente_id' => Expediente::factory(),
            'user_id' => User::factory(),
            'tipo' => $tipo,
            'titulo' => $this->faker->sentence(),
            'categoria_id' => BitacoraCategoria::factory(),
            'descripcion' => $this->faker->paragraph(),
            'tiempo_dedicado' => $this->faker->numberBetween(15, 180),
            'estado' => $estado,
            'fecha_limite' => $fechaLimite,
            'prioridad_fecha' => $prioridadFecha,
            'fecha_completado' => $fechaCompletado,
            'fecha_reactivacion' => $fechaReactivacion,
            'created_at' => $fechaCreacion,
            'updated_at' => $fechaActualizacion,
        ];
    }

    /**
     * Configurar la bitácora como tipo normal
     */
    public function normal(): Factory
    {
        return $this->state(function (array $attributes) {
            // Asegurarnos de que created_at existe
            $fechaCreacion = $attributes['created_at'] ?? Carbon::now('UTC')->subDays(mt_rand(1, 180));
            
            return [
                'tipo' => 'normal',
                'titulo' => $attributes['titulo'] ?? $this->faker->sentence(),
                'estado' => 'completado',
                'fecha_completado' => $fechaCreacion,
                'fecha_limite' => null,
                'prioridad_fecha' => null,
            ];
        });
    }

    /**
     * Configurar la bitácora como tipo seguimiento
     */
    public function seguimiento(): Factory
    {
        return $this->state(function (array $attributes) {
            // Asegurarnos de que tenemos una fecha de creación válida
            $fechaCreacion = $attributes['created_at'] ?? Carbon::now('UTC')->subDays(mt_rand(1, 180));
            
            // Asegurarnos de que el título se mantiene
            $titulo = $attributes['titulo'] ?? $this->faker->sentence();
            
            // La fecha límite debe ser futura
            $now = Carbon::now('UTC');
            $fechaLimite = Carbon::createFromTimestamp(
                mt_rand($now->timestamp, $now->copy()->addMonths(3)->timestamp)
            )->format('Y-m-d');

            return [
                'tipo' => 'seguimiento',
                'titulo' => $titulo,
                'fecha_limite' => $fechaLimite,
                'prioridad_fecha' => $this->faker->randomElement(['normal', 'importante', 'critica']),
            ];
        });
    }

    /**
     * Configurar la bitácora como completada
     */
    public function completada(): Factory
    {
        return $this->state(function (array $attributes) {
            // Asegurarnos de que tenemos una fecha de creación válida
            $fechaCreacion = $attributes['created_at'] ?? Carbon::now('UTC')->subDays(mt_rand(1, 180));
            
            // Asegurarnos de que el título se mantiene
            $titulo = $attributes['titulo'] ?? $this->faker->sentence();
            
            // Generar fecha de completado entre la creación y hace 1 hora
            $now = Carbon::now('UTC');
            $fechaCompletado = Carbon::createFromTimestamp(
                mt_rand($fechaCreacion->timestamp, $now->copy()->subHour()->timestamp)
            );
            
            return [
                'titulo' => $titulo,
                'estado' => 'completado',
                'fecha_completado' => $fechaCompletado,
            ];
        });
    }

    /**
     * Configurar la bitácora como reactivada
     */
    public function reactivada(): Factory
    {
        return $this->state(function (array $attributes) {
            // Asegurarnos de que tenemos una fecha de creación válida
            $fechaCreacion = $attributes['created_at'] ?? Carbon::now('UTC')->subDays(mt_rand(1, 180));
            
            // Asegurarnos de que el título se mantiene
            $titulo = $attributes['titulo'] ?? $this->faker->sentence();
            
            // Generar fecha de completado entre la creación y hace 2 horas
            $now = Carbon::now('UTC');
            $fechaCompletado = Carbon::createFromTimestamp(
                mt_rand($fechaCreacion->timestamp, $now->copy()->subHours(2)->timestamp)
            );
            
            // Generar fecha de reactivación entre el completado y hace 1 hora
            $fechaReactivacion = Carbon::createFromTimestamp(
                mt_rand($fechaCompletado->timestamp, $now->copy()->subHour()->timestamp)
            );
            
            return [
                'titulo' => $titulo,
                'estado' => 'en_proceso',
                'fecha_completado' => null,
                'fecha_reactivacion' => $fechaReactivacion,
            ];
        });
    }
}

