<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\follow;


class followSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $follow = new follow();

        $follow->id_client = '1';
        $follow->fecha = '2021-10-10';
        $follow->comentarios = 'Comentarios 1';
        $follow->fecha_recepcion = '2021-10-10';
        $follow->fecha_limite = '2021-10-10';
        $follow->tiempo = '1 minuto';
        $follow->estatus = 'activo';
        $follow->persona_responsable = 'carolina';
        $follow->save();

        
        follow::factory(100)->create();
    }
}
