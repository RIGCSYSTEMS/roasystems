<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Database\Factories\UserFactory;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User();

        $user->name = 'Admin';
        $user->email = 'rigc.ivan@gmail.com';
        $user->password = bcrypt('12345678');
        $user->role = 'ADMIN';
        $user->save();
        $user = new User();
        $user->name = 'Director';
        $user->email = 'rigc.ivan@director.com';
        $user->password = bcrypt('12345678');
        $user->role = 'DIRECTOR';
        $user->save();
        $user = new User();
        $user->name = 'Abogado';
        $user->email = 'rigc.ivan@abogado.com';
        $user->password = bcrypt('12345678');
        $user->role = 'ABOGADO';
        $user->save();
        $user = new User();
        $user->name = 'Cliente';
        $user->email = 'rigc.ivan@cliente.com';
        $user->password = bcrypt('12345678');
        $user->role = 'CLIENTE';
 
         $user->save();

         User::factory(30)->create();
        
    }
}
