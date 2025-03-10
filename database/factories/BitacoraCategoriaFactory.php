<?php

namespace Database\Factories;

use App\Models\BitacoraCategoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class BitacoraCategoriaFactory extends Factory
{
    protected $model = BitacoraCategoria::class;

    public function definition(): array
    {
        $categorias = [
            'llamada' => 'Llamada telefónica',
            'correo' => 'Correo electrónico',
            'reunion' => 'Reunión con cliente',
            'documento' => 'Preparación de documento',
            'audiencia' => 'Audiencia',
            'investigacion' => 'Investigación',
            'pago' => 'Registro de pago',
            'otro' => 'Otro'
        ];

        $codigo = $this->faker->randomElement(array_keys($categorias));
        $nombre = $categorias[$codigo];

        return [
            'codigo' => $codigo,
            'nombre' => $nombre,
            'descripcion' => $this->faker->sentence(),
            'activo' => true,
        ];
    }
}

