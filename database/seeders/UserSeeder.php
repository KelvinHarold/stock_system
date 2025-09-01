<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin User
        User::create([
            'name' => 'Lekei',
            'email' => 'lekeiagrovets@gmail.com',
            'password' => Hash::make('lekeiagrovets@12345'),
        ]);
    }
}
