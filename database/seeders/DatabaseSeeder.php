<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'lastName' => 'Ivan',
            'firstName' => 'Ivanov',
            'email' => 'ivan@ya.ru',
        ]);

        User::factory(29)->create();
    }
}
