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
 
         $user->save();

         User::factory(10)->create();
        
    }
}
