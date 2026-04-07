<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Room;
use App\Models\Guest;
use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder {
    public function run(): void {

        // Admin account
        $admin = User::create([
            'name'     => 'Hotel Admin',
            'email'    => 'admin@hotel.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // Staff account
        $staffUser = User::create([
            'name'     => 'Jane Staff',
            'email'    => 'staff@hotel.com',
            'password' => Hash::make('password'),
            'role'     => 'staff',
        ]);

        // Guest account
        User::create([
            'name'     => 'John Guest',
            'email'    => 'guest@hotel.com',
            'password' => Hash::make('password'),
            'role'     => 'guest',
        ]);

        // Rooms
        $rooms = [
            ['room_number'=>'101','type'=>'standard','description'=>'Cozy standard room with garden view.','price_per_night'=>1500,'capacity'=>2,'status'=>'available'],
            ['room_number'=>'102','type'=>'standard','description'=>'Comfortable room with city view.','price_per_night'=>1500,'capacity'=>2,'status'=>'available'],
            ['room_number'=>'201','type'=>'deluxe','description'=>'Spacious deluxe room with balcony.','price_per_night'=>3000,'capacity'=>3,'status'=>'available'],
            ['room_number'=>'202','type'=>'deluxe','description'=>'Deluxe room with ocean view.','price_per_night'=>3500,'capacity'=>3,'status'=>'occupied'],
            ['room_number'=>'301','type'=>'suite','description'=>'Luxury suite with living area.','price_per_night'=>7000,'capacity'=>4,'status'=>'available'],
            ['room_number'=>'401','type'=>'presidential','description'=>'Top-floor presidential suite.','price_per_night'=>15000,'capacity'=>6,'status'=>'maintenance'],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }

        // Guests
        $guest1 = Guest::create([
            'first_name'=>'Maria','last_name'=>'Santos',
            'email'=>'maria.santos@email.com','phone'=>'09171234567',
            'address'=>'Quezon City, Metro Manila',
            'id_type'=>'Passport','id_number'=>'P1234567',
        ]);

        $guest2 = Guest::create([
            'first_name'=>'Carlos','last_name'=>'Reyes',
            'email'=>'carlos.reyes@email.com','phone'=>'09281234567',
            'address'=>'Cebu City, Cebu',
            'id_type'=>'Driver\'s License','id_number'=>'N01-23-456789',
        ]);

        // Staff
        Staff::create([
            'user_id'    => $staffUser->id,
            'position'   => 'Front Desk Officer',
            'department' => 'Reception',
            'phone'      => '09101234567',
            'hired_at'   => '2023-01-15',
            'status'     => 'active',
        ]);
    }
}