<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {

        User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'type' => 'admin',
            'password' => Hash::make('password'),
        ]);


        User::create([
            'name' => 'User Test',
            'email' => 'user@test.com',
            'type' => 'user',
            'password' => Hash::make('password'),
        ]);
    }
}