<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Hip Hop', 'description' => 'Modern hip hop beats with heavy 808s'],
            ['name' => 'Trap', 'description' => 'Dark and energetic trap instrumentals'],
            ['name' => 'R&B', 'description' => 'Smooth R&B and soulful beats'],
            ['name' => 'Pop', 'description' => 'Catchy pop melodies and hooks'],
            ['name' => 'Electronic', 'description' => 'EDM and electronic dance beats'],
            ['name' => 'Lo-Fi', 'description' => 'Chill lo-fi hip hop for studying'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}