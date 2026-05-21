<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roomTypes = [
            [
                'name' => 'Standard',
                'description' => 'Basic room with essential amenities',
                'base_price' => 1500.00,
                'capacity' => 2,
                'amenities' => ['WiFi', 'TV', 'Air Conditioning'],
            ],
            [
                'name' => 'Deluxe',
                'description' => 'Comfortable room with additional amenities',
                'base_price' => 2500.00,
                'capacity' => 2,
                'amenities' => ['WiFi', 'TV', 'Air Conditioning', 'Mini Bar', 'Balcony'],
            ],
            [
                'name' => 'Suite',
                'description' => 'Spacious suite with premium amenities',
                'base_price' => 4000.00,
                'capacity' => 4,
                'amenities' => ['WiFi', 'TV', 'Air Conditioning', 'Mini Bar', 'Balcony', 'Kitchenette'],
            ],
        ];

        foreach ($roomTypes as $type) {
            \App\Models\RoomType::create($type);
        }
    }
}
