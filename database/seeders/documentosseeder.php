<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\documento;
use App\Models\Client;
class documentosseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::all()->each(function ($client) {
            if (!Documento::find($client->id)) {
                Documento::factory()->create(['id' => $client->id]);
            }
        });
    }
}
