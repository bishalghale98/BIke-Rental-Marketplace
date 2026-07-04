<?php

namespace Database\Seeders;

use App\Models\BikeCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BikeCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Scooter', 'description' => 'Small engine scooters for city commuting'],
            ['name' => 'Commuter', 'description' => 'Economical bikes for daily commuting'],
            ['name' => 'Cruiser', 'description' => 'Comfortable bikes for relaxed rides'],
            ['name' => 'Sport', 'description' => 'High-performance sport bikes'],
            ['name' => 'Touring', 'description' => 'Long-distance touring motorcycles'],
            ['name' => 'Dirt / Off-road', 'description' => 'Off-road and adventure bikes'],
            ['name' => 'Electric', 'description' => 'Electric bikes and e-scooters'],
        ];

        foreach ($categories as $category) {
            BikeCategory::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'is_active' => true,
            ]);
        }
    }
}
