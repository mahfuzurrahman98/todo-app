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
        // Get predefined users and create them
        $factory = User::factory();
        $predefinedUsers = $factory->getPredefinedUsers();

        foreach ($predefinedUsers as $user) {
            User::create($user);
        }

        // For testing, create additional random users if needed
        if (app()->environment('testing')) {
            User::factory()->count(3)->create();
        }
    }
}
