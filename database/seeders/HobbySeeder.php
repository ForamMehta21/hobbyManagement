<?php

namespace Database\Seeders;

use App\Models\Hobby;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class HobbySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hobbies = [
            ['name' => 'Reading'],
            ['name' => 'Traveling'],
            ['name' => 'Cooking'],
            ['name' => 'Sports'],
            ['name' => 'Music'],
        ];

        // Insert hobbies into the database
        foreach ($hobbies as $hobby) {
            Hobby::firstOrCreate($hobby);
        }
    }
}
