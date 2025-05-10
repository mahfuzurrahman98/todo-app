<?php

namespace Database\Seeders;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Database\Seeder;

class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get predefined todos and create them
        $factory = Todo::factory();
        $predefinedTodos = $factory->getPredefinedTodos();

        foreach ($predefinedTodos as $todo) {
            Todo::create($todo);
        }

        // For testing, create additional random todos if needed
        if (app()->environment('testing')) {
            // Create 5 todos for each user
            User::all()->each(function ($user) {
                Todo::factory()->count(5)->create([
                    'user_id' => $user->id
                ]);
            });
        }
    }
}
