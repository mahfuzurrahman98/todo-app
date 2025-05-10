<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run the seeders in order
        $this->call([
            UserSeeder::class,
            TodoSeeder::class,
        ]);
    }
}
