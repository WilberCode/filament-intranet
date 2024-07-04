<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

         \App\Models\User::factory()->create([
             'name' => 'Carlos',
             'email' => 'test@example.com',
             'password'=>  Hash::make('12345'),
         ]);
         \App\Models\User::factory()->create([
            'name' => 'Wilber',
            'email' => 'admin@wilcode.com',
            'password'=>  Hash::make('12345'),
        ]);
    }
}
