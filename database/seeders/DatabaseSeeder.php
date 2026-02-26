<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'is_admin' => true,
            'role' => 'admin'
        ]);

        User::factory()->create([
            'email' => 'test@example.com',
            'role' => 'organiser'
        ]);
        User::factory()->create([
            'email' => 'test2@example.com',
            'role' => 'organiser'
        ]);
        User::factory()->create([
            'email' => 'test3@example.com',
            'role' => 'organiser'
        ]);
        User::factory(10)->create([
            'role' => 'attendee'
        ]);
    }
}
