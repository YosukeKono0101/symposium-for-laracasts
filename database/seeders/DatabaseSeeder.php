<?php

namespace Database\Seeders;

use App\Models\Conference;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Talk;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()
            ->has(Talk::factory()->count(5))
            ->create([
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]);

        Conference::factory()->count(5)->create();
    }
}
