<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roomTypes = \App\Models\RoomType::all();

        $photos = [
            'https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1591088398332-8a7791972843?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1595576508898-0ad5c879a061?auto=format&fit=crop&w=1200&q=80',
        ];

        $rooms = [];
        foreach ($roomTypes as $type) {
            for ($i = 1; $i <= 2; $i++) {
                $rooms[] = [
                    'room_number' => $type->id * 100 + $i,
                    'room_type_id' => $type->id,
                    'description' => "A comfortable {$type->name} room with curated guest amenities.",
                    'status' => 'available',
                    'image' => $photos[(count($rooms)) % count($photos)],
                ];
            }
        }

        foreach ($rooms as $room) {
            \App\Models\Room::updateOrCreate(['room_number' => $room['room_number']], $room);
        }
    }
}
