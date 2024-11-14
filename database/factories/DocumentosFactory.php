<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Documento;
use App\Models\client;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DocumentosFactory extends Factory
{
    protected $model = Documento::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $client = Client::inRandomOrder()->first();

        return [
           'id' => $client->id,
            // 'identificacion' => $this->faker->randomElement([null, 'https://picsum.photos/200/300']),
            // 'pasaporte' => $this->faker->randomElement([null, 'https://picsum.photos/200/300']),
            // 'permiso_de_trabajo' => $this->faker->randomElement([null, 'https://picsum.photos/200/300']),
            // 'hoja_marron' => $this->faker->randomElement([null, 'https://picsum.photos/200/300']),
            // 'pruebas' => $this->faker->randomElement([null, 'https://picsum.photos/200/300']),
            // 'historia' =>  $this->faker->randomElement([null, 'https://picsum.photos/200/300']),
            // 'residencia_permanente' => $this->faker->randomElement([null, 'https://picsum.photos/200/300']),
            // 'caq' => $this->faker->randomElement([null, 'https://picsum.photos/200/300']),
            // 'extras' => $this->faker->randomElement([null, 'https://picsum.photos/200/300']),
        ];
    }
}
