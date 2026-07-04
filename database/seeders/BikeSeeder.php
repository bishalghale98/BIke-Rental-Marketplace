<?php

namespace Database\Seeders;

use App\Models\Bike;
use App\Models\BikeImage;
use App\Models\User;
use Illuminate\Database\Seeder;

class BikeSeeder extends Seeder
{
    public function run(): void
    {
        $company = User::where('email', 'company@example.com')->first()?->company;

        if (!$company) {
            $this->command->error('Company user not found. Run TestUserSeeder first.');
            return;
        }

        $bikes = [
            [
                'category_id' => 2,
                'name' => 'Honda CB Shine',
                'slug' => 'honda-cb-shine',
                'brand' => 'Honda',
                'model' => 'CB Shine SP',
                'year' => 2023,
                'engine_capacity' => '125cc',
                'fuel_type' => 'Petrol',
                'transmission' => 'Manual',
                'mileage' => '65 kmpl',
                'color' => 'Black',
                'bike_number' => 'BA 1 PA 1234',
                'registration_number' => 'REG-001',
                'description' => 'Well-maintained Honda CB Shine SP. Perfect for daily commuting in the city. Smooth engine and great fuel economy.',
                'features' => ['Electric Start', 'LED Tail Light', 'Tubeless Tyres', 'Kick Start', 'Engine Kill Switch'],
                'specifications' => ['Engine: 124cc', 'Power: 10.74 PS', 'Torque: 11 Nm', 'Fuel Tank: 10.5L', 'Weight: 123 kg'],
                'rental_rules' => ['Security deposit required: NPR 5,000', 'Return with full fuel tank', 'Late return fee: NPR 500/hr', 'Damage cost as per assessment'],
                'hourly_price' => 150,
                'daily_price' => 800,
                'weekly_price' => 4000,
                'images' => [
                    ['path' => 'bikes/sample/honda-cb-shine-1.jpg', 'primary' => true],
                    ['path' => 'bikes/sample/honda-cb-shine-2.jpg', 'primary' => false],
                ],
            ],
            [
                'category_id' => 1,
                'name' => 'Yamaha Ray ZR',
                'slug' => 'yamaha-ray-zr',
                'brand' => 'Yamaha',
                'model' => 'Ray ZR 125',
                'year' => 2024,
                'engine_capacity' => '125cc',
                'fuel_type' => 'Petrol',
                'transmission' => 'Automatic',
                'mileage' => '55 kmpl',
                'color' => 'Blue',
                'bike_number' => 'BA 2 PA 5678',
                'registration_number' => 'REG-002',
                'description' => 'Sleek and stylish scooter from Yamaha. Great for city rides with ample under-seat storage.',
                'features' => ['Electric Start', 'LED Headlight', 'Digital Speedometer', 'USB Charger', 'Side Stand Engine Cut-off'],
                'specifications' => ['Engine: 125cc', 'Power: 8.04 PS', 'Torque: 9.7 Nm', 'Fuel Tank: 5.2L', 'Weight: 99 kg'],
                'rental_rules' => ['Security deposit required: NPR 4,000', 'Return with full fuel tank', 'Late return fee: NPR 300/hr', 'Helmet included'],
                'hourly_price' => 120,
                'daily_price' => 600,
                'weekly_price' => 3000,
                'images' => [
                    ['path' => 'bikes/sample/yamaha-ray-zr-1.jpg', 'primary' => true],
                    ['path' => 'bikes/sample/yamaha-ray-zr-2.jpg', 'primary' => false],
                ],
            ],
            [
                'category_id' => 4,
                'name' => 'KTM Duke 200',
                'slug' => 'ktm-duke-200',
                'brand' => 'KTM',
                'model' => '200 Duke',
                'year' => 2024,
                'engine_capacity' => '200cc',
                'fuel_type' => 'Petrol',
                'transmission' => 'Manual',
                'mileage' => '35 kmpl',
                'color' => 'Orange',
                'bike_number' => 'BA 3 PA 9012',
                'registration_number' => 'REG-003',
                'description' => 'Powerful and aggressive street naked bike. Perfect for enthusiasts who love performance and style.',
                'features' => ['Electric Start', 'LED Headlight', 'Digital Instrument Cluster', 'ABS', 'Slipper Clutch'],
                'specifications' => ['Engine: 199.5cc', 'Power: 25 PS', 'Torque: 19.5 Nm', 'Fuel Tank: 13.5L', 'Weight: 159 kg'],
                'rental_rules' => ['Security deposit required: NPR 10,000', 'Valid motorcycle license mandatory', 'Return with full fuel tank', 'Late return fee: NPR 800/hr', 'Damage cost as per assessment'],
                'hourly_price' => 300,
                'daily_price' => 1500,
                'weekly_price' => 7000,
                'images' => [
                    ['path' => 'bikes/sample/ktm-duke-200-1.jpg', 'primary' => true],
                    ['path' => 'bikes/sample/ktm-duke-200-2.jpg', 'primary' => false],
                ],
            ],
            [
                'category_id' => 3,
                'name' => 'Royal Enfield Classic 350',
                'slug' => 'royal-enfield-classic-350',
                'brand' => 'Royal Enfield',
                'model' => 'Classic 350',
                'year' => 2023,
                'engine_capacity' => '350cc',
                'fuel_type' => 'Petrol',
                'transmission' => 'Manual',
                'mileage' => '40 kmpl',
                'color' => 'Military Green',
                'bike_number' => 'BA 4 PA 3456',
                'registration_number' => 'REG-004',
                'description' => 'Iconic Royal Enfield Classic 350. Timeless design with thumping exhaust note. Ideal for long weekend rides.',
                'features' => ['Electric Start', 'ABS', 'Semi-digital Speedometer', 'Classic Round Headlight', 'Tubeless Tyres'],
                'specifications' => ['Engine: 349cc', 'Power: 20.2 PS', 'Torque: 27 Nm', 'Fuel Tank: 13L', 'Weight: 195 kg'],
                'rental_rules' => ['Security deposit required: NPR 12,000', 'Valid motorcycle license mandatory', 'Return with full fuel tank', 'Late return fee: NPR 1,000/hr'],
                'hourly_price' => 400,
                'daily_price' => 2000,
                'weekly_price' => 10000,
                'images' => [
                    ['path' => 'bikes/sample/re-classic-350-1.jpg', 'primary' => true],
                    ['path' => 'bikes/sample/re-classic-350-2.jpg', 'primary' => false],
                ],
            ],
            [
                'category_id' => 7,
                'name' => 'Ola S1 Pro',
                'slug' => 'ola-s1-pro',
                'brand' => 'Ola Electric',
                'model' => 'S1 Pro',
                'year' => 2024,
                'engine_capacity' => null,
                'fuel_type' => 'Electric',
                'transmission' => 'Automatic',
                'mileage' => '150 km/charge',
                'color' => 'White',
                'bike_number' => 'BA 5 PA 7890',
                'registration_number' => 'REG-005',
                'description' => 'Premium electric scooter from Ola. Hyper mode, spacious boot, and connected features. Eco-friendly commuting.',
                'features' => ['Electric Motor', 'Digital Touchscreen', 'Eco/Normal/Sport Modes', 'Reverse Mode', 'Cruise Control'],
                'specifications' => ['Motor: 8.5 kW', 'Battery: 4 kWh', 'Range: 150 km', 'Top Speed: 115 km/h', '0-40 km/h: 2.9s'],
                'rental_rules' => ['Security deposit required: NPR 3,000', 'Return with minimum 20% charge', 'Charging cable included', 'Late return fee: NPR 200/hr'],
                'hourly_price' => 100,
                'daily_price' => 500,
                'weekly_price' => 2500,
                'images' => [
                    ['path' => 'bikes/sample/ola-s1-pro-1.jpg', 'primary' => true],
                    ['path' => 'bikes/sample/ola-s1-pro-2.jpg', 'primary' => false],
                ],
            ],
            [
                'category_id' => 5,
                'name' => 'Honda CB500X',
                'slug' => 'honda-cb500x',
                'brand' => 'Honda',
                'model' => 'CB500X',
                'year' => 2023,
                'engine_capacity' => '500cc',
                'fuel_type' => 'Petrol',
                'transmission' => 'Manual',
                'mileage' => '25 kmpl',
                'color' => 'Red',
                'bike_number' => 'BA 6 PA 1122',
                'registration_number' => 'REG-006',
                'description' => 'Adventure touring bike from Honda. Comfortable ergonomics and reliable engine. Ready for long highway journeys.',
                'features' => ['Electric Start', 'ABS', 'Adjustable Windscreen', 'Pannier Mounts', 'LED Lighting'],
                'specifications' => ['Engine: 471cc', 'Power: 47 PS', 'Torque: 43 Nm', 'Fuel Tank: 17.5L', 'Weight: 189 kg'],
                'rental_rules' => ['Security deposit required: NPR 15,000', 'Valid motorcycle license mandatory', 'Return with full fuel tank', 'Late return fee: NPR 1,500/hr', 'Damage cost as per assessment'],
                'hourly_price' => 500,
                'daily_price' => 3000,
                'weekly_price' => 15000,
                'images' => [
                    ['path' => 'bikes/sample/honda-cb500x-1.jpg', 'primary' => true],
                    ['path' => 'bikes/sample/honda-cb500x-2.jpg', 'primary' => false],
                ],
            ],
        ];

        foreach ($bikes as $data) {
            $images = $data['images'];
            unset($data['images']);

            $data['company_id'] = $company->id;
            $data['is_available'] = true;
            $data['status'] = 'active';

            $bike = Bike::create($data);

            foreach ($images as $img) {
                BikeImage::create([
                    'bike_id' => $bike->id,
                    'image_path' => $img['path'],
                    'is_primary' => $img['primary'],
                    'sort_order' => $img['primary'] ? 0 : 1,
                ]);
            }

            $this->command->info("Created bike: {$bike->name}");
        }
    }
}
