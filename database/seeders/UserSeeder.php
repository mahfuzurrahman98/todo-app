<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create two users
        // Another with email mahfuz@test.com
        // One with email samia@test.com
        // Password is Pass@123

        User::create([
            'name' => 'Mahfuz',
            'email' => 'mahfuz@test.com',
            'password' => Hash::make('Pass@123'),
        ]);

        User::create([
            'name' => 'Samia',
            'email' => 'samia@test.com',
            'password' => Hash::make('Pass@123'),
        ]);
    }
}
