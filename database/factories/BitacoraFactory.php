<?php

namespace Database\Factories;

use App\Models\Bitacora;
use App\Models\BitacoraCategoria;
use App\Models\User;
use App\Models\Expediente;
use Illuminate\Database\Eloquent\Factories\Factory;

class BitacoraFactory extends Factory
{
    protected $model = Bitacora::class;

    public function definition(): array
    {
        // Fecha de creación entre hace 6 meses y hoy
        $fechaCreacion = $this->faker->dateTimeBetween('-6 months', '-1 day');
        
        $tipo = $this->faker->randomElement(['normal', 'seguimiento']);
        $estado = $this->faker->randomElement(['completado', 'en_proceso', 'pendiente']);
        
        // Fecha de completado debe ser posterior a la fecha de creación
        $fechaCompletado = null;
        if ($estado === 'completado') {
            $fechaCompletado = $this->faker->dateTimeBetween($fechaCreacion, '-1 minute');
        }
        
        // Fecha de reactivación debe ser posterior a la fecha de completado (si existe)
        $fechaReactivacion = null;
        if ($estado !== 'completado' && $this->faker->boolean(20)) {
            $fechaReactivacion = $this->faker->dateTimeBetween(
                $fechaCompletado ?? $fechaCreacion,
                '-1 minute'
            );
        }
        
        // Fecha límite debe ser posterior a la fecha de creación para bitácoras de seguimiento
        $fechaLimite = null;
        $prioridadFecha = null;
        if ($tipo === 'seguimiento') {
            // Si está completada, la fecha límite debe ser anterior a la fecha de completado
            if ($estado === 'completado' && $fechaCompletado) {
                $fechaLimite = $this->faker->dateTimeBetween($fechaCreacion, $fechaCompletado);
            } else {
                // Si no está completada, la fecha límite debe ser futura
                $fechaLimite = $this->faker->dateTimeBetween('+1 day', '+3 months');
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
            'updated_at' => $this->faker->dateTimeBetween($fechaCreacion, '-1 minute'),
        ];
    }

    /**
     * Configurar la bitácora como tipo normal
     */
    public function normal(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'tipo' => 'normal',
                'estado' => 'completado',
                'fecha_completado' => $attributes['created_at'],
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
            $fechaCreacion = $attributes['created_at'] instanceof \DateTime 
                ? $attributes['created_at'] 
                : new \DateTime($attributes['created_at']);

            // La fecha límite debe ser futura
            $fechaLimite = $this->faker->dateTimeBetween('+1 day', '+3 months');

            return [
                'tipo' => 'seguimiento',
                'fecha_limite' => $fechaLimite->format('Y-m-d'),
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
            $fechaCreacion = $attributes['created_at'] instanceof \DateTime 
                ? $attributes['created_at'] 
                : new \DateTime($attributes['created_at']);
            
            // Si hay fecha límite, asegurarnos de que es un objeto DateTime
            $fechaLimite = null;
            if (!empty($attributes['fecha_limite'])) {
                $fechaLimite = $attributes['fecha_limite'] instanceof \DateTime 
                    ? $attributes['fecha_limite'] 
                    : new \DateTime($attributes['fecha_limite']);
            }
            
            // Generar fecha de completado
            $fechaCompletado = $fechaLimite 
                ? $this->faker->dateTimeBetween($fechaCreacion, min($fechaLimite, new \DateTime('-1 minute')))
                : $this->faker->dateTimeBetween($fechaCreacion, '-1 minute');
            
            return [
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
            $fechaCreacion = $attributes['created_at'] instanceof \DateTime 
                ? $attributes['created_at'] 
                : new \DateTime($attributes['created_at']);
            
            // Calcular una fecha intermedia para el completado
            $fechaIntermedia = clone $fechaCreacion;
            $fechaIntermedia->modify('+1 hour'); // Asegurar al menos una hora después de la creación
            
            // Generar fecha de completado entre la creación y hace un minuto
            $fechaCompletado = $this->faker->dateTimeBetween($fechaIntermedia, '-1 hour');
            
            // Generar fecha de reactivación después del completado
            $fechaReactivacion = $this->faker->dateTimeBetween($fechaCompletado, '-1 minute');
            
            return [
                'estado' => 'en_proceso',
                'fecha_completado' => null, // La fecha de completado se elimina al reactivar
                'fecha_reactivacion' => $fechaReactivacion,
            ];
        });
    }
}

