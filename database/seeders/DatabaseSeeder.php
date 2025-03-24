<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\PermisionSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(PermisionSeeder::class);

        User::factory()->create([
            'name' => 'David',
            'email' => 'david@example.com',
            'password' => Hash::make('123456789'),
        ])->assignRole('god');

        User::factory()->create([
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => Hash::make('123456789'),
        ])->assignRole('admin');

        User::factory()->create([
            'name' => 'Jane',
            'email' => 'jane@example.com',
            'password' => Hash::make('123456789'),
        ])->assignRole('user');

        $this->call(ClassSeeder::class);
    }
}
