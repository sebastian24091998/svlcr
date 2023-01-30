<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //creacion de usuarios
        User::create([
            'name' => 'Sebastian',
            'phone' => '78896354',
            'email' => 'jhonmollo31@gmail.com',
            'profile' => 'Admin',
            'status' => 'ACTIVE',
            'password' => bcrypt('123')
        ]);
        
        User::create([
            'name' => 'Liz Mendez',
            'phone' => '78896369',
            'email' => 'lizm@gmail.com',
            'profile' => 'Vendedor',
            'status' => 'ACTIVE',
            'password' => bcrypt('123')
        ]);
    }
}
