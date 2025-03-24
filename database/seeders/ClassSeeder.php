<?php

namespace Database\Seeders;

use App\Models\GymClass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        GymClass::create([
            'name' => 'Yoga',
            'description' => 'Yoga class',
            'location' => '123 Main St',
            'price' => 10.00,
            'duration' => 60,
            'start_time' => '2025-03-21 10:00:00',
            'end_time' => '2025-03-21 11:00:00',
            'max_participants' => 10
        ]);
    }
}
