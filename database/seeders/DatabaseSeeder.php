<?php

namespace Database\Seeders;

use App\Models\Guest;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Service;
use App\Models\Staff;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = collect([
            ['name' => 'guest', 'description' => 'Registers an account, browses available rooms, makes/cancels reservations, requests services, makes payments, and leaves feedback.'],
            ['name' => 'receptionist', 'description' => 'Manages check-ins/check-outs, guest reservations, room status, billing, payments, housekeeping tasks, and walk-in bookings.'],
            ['name' => 'admin', 'description' => 'Full system access — manages users, rooms, pricing, employees, reports, analytics, system settings, and overrides.'],
        ])->mapWithKeys(fn ($role) => [
            $role['name'] => UserRole::updateOrCreate(['name' => $role['name']], $role),
        ]);

        $roomTypes = collect([
            [
                'name' => 'Standard',
                'description' => 'Cozy room for short stays and solo travelers.',
                'base_price' => 1500,
                'capacity' => 2,
                'amenities' => ['High-speed Wi-Fi', 'Smart TV', 'Writing desk', 'Hot and cold shower', 'Daily housekeeping'],
            ],
            [
                'name' => 'Deluxe',
                'description' => 'Spacious room with premium comfort and city views.',
                'base_price' => 3200,
                'capacity' => 3,
                'amenities' => ['High-speed Wi-Fi', 'Smart TV', 'Mini bar', 'Private balcony', 'Coffee and tea set', 'Premium toiletries'],
            ],
            [
                'name' => 'Suite',
                'description' => 'Luxury suite with lounge area and extended stay comfort.',
                'base_price' => 7200,
                'capacity' => 4,
                'amenities' => ['High-speed Wi-Fi', 'Smart TV', 'Separate lounge area', 'Bathtub', 'Kitchenette', 'Complimentary breakfast'],
            ],
        ])->mapWithKeys(fn ($roomType) => [
            strtolower($roomType['name']) => RoomType::updateOrCreate(['name' => $roomType['name']], $roomType),
        ]);

        $admin = User::updateOrCreate(
            ['email' => 'admin@hotel.com'],
            [
                'name' => 'Hotel Admin',
                'password' => Hash::make('password'),
                'user_role_id' => $roles['admin']->id,
                'phone' => '09090000001',
                'is_active' => true,
            ]
        );

        $receptionist1 = User::updateOrCreate(
            ['email' => 'reception@hotel.com'],
            [
                'name' => 'Jane Reception',
                'password' => Hash::make('password'),
                'user_role_id' => $roles['receptionist']->id,
                'phone' => '09090000002',
                'is_active' => true,
            ]
        );

        $receptionist2 = User::updateOrCreate(
            ['email' => 'operations@hotel.com'],
            [
                'name' => 'Marco Operations',
                'password' => Hash::make('password'),
                'user_role_id' => $roles['receptionist']->id,
                'phone' => '09090000003',
                'is_active' => true,
            ]
        );

        $guest = User::updateOrCreate(
            ['email' => 'guest@hotel.com'],
            [
                'name' => 'John Guest',
                'password' => Hash::make('password'),
                'user_role_id' => $roles['guest']->id,
                'phone' => '09090000005',
                'is_active' => true,
            ]
        );

        foreach ([
            [
                'room_number' => '101',
                'room_type_id' => $roomTypes['standard']->id,
                'description' => 'Cozy standard room with a warm garden view, queen bed, work desk, and easy access to the lobby.',
                'status' => 'available',
                'image' => 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'room_number' => '102',
                'room_type_id' => $roomTypes['standard']->id,
                'description' => 'Bright standard room with city-facing windows, compact workspace, and comfortable overnight essentials.',
                'status' => 'available',
                'image' => 'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'room_number' => '201',
                'room_type_id' => $roomTypes['deluxe']->id,
                'description' => 'Spacious deluxe room with a private balcony, soft seating corner, and minibar for relaxed stays.',
                'status' => 'available',
                'image' => 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'room_number' => '202',
                'room_type_id' => $roomTypes['deluxe']->id,
                'description' => 'Deluxe ocean-view room with sunrise-facing balcony, premium linens, and generous floor space.',
                'status' => 'available',
                'image' => 'https://images.unsplash.com/photo-1591088398332-8a7791972843?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'room_number' => '301',
                'room_type_id' => $roomTypes['suite']->id,
                'description' => 'Luxury suite with a separate lounge area, deep bathtub, kitchenette, and breakfast service.',
                'status' => 'available',
                'image' => 'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'room_number' => '302',
                'room_type_id' => $roomTypes['suite']->id,
                'description' => 'Premium family suite with expanded living space, dining nook, bathtub, and a quiet upper-floor view.',
                'status' => 'available',
                'image' => 'https://images.unsplash.com/photo-1595576508898-0ad5c879a061?auto=format&fit=crop&w=1200&q=80',
            ],
        ] as $room) {
            Room::updateOrCreate(['room_number' => $room['room_number']], $room);
        }

        Guest::updateOrCreate(
            ['email' => 'maria.santos@email.com'],
            [
                'first_name' => 'Maria',
                'last_name' => 'Santos',
                'phone' => '09171234567',
                'address' => 'Quezon City, Metro Manila',
                'id_type' => 'Passport',
                'id_number' => 'P1234567',
            ]
        );

        Guest::updateOrCreate(
            ['email' => 'carlos.reyes@email.com'],
            [
                'first_name' => 'Carlos',
                'last_name' => 'Reyes',
                'phone' => '09281234567',
                'address' => 'Cebu City, Cebu',
                'id_type' => 'Driver\'s License',
                'id_number' => 'N01-23-456789',
            ]
        );

        Guest::updateOrCreate(
            ['email' => $guest->email],
            [
                'first_name' => 'John',
                'last_name' => 'Guest',
                'phone' => $guest->phone,
                'address' => 'Pasig City, Metro Manila',
                'id_type' => 'Passport',
                'id_number' => 'P7654321',
            ]
        );

        foreach ([
            [
                'user_id' => $admin->id,
                'position' => 'General Manager',
                'department' => 'Administration',
                'phone' => '09101234560',
                'hired_at' => '2022-01-01',
                'status' => 'active',
            ],
            [
                'user_id' => $receptionist1->id,
                'position' => 'Front Desk Officer',
                'department' => 'Reception',
                'phone' => '09101234567',
                'hired_at' => '2023-01-15',
                'status' => 'active',
            ],
            [
                'user_id' => $receptionist2->id,
                'position' => 'Operations Officer',
                'department' => 'Operations',
                'phone' => '09101234568',
                'hired_at' => '2023-06-12',
                'status' => 'active',
            ],
        ] as $staffMember) {
            Staff::updateOrCreate(['user_id' => $staffMember['user_id']], $staffMember);
        }

        foreach ([
            ['name' => 'Breakfast Buffet', 'description' => 'Daily breakfast service', 'price' => 450],
            ['name' => 'Airport Transfer', 'description' => 'One-way airport shuttle', 'price' => 900],
            ['name' => 'Laundry Service', 'description' => 'Same-day laundry package', 'price' => 350],
        ] as $service) {
            Service::updateOrCreate(['name' => $service['name']], $service);
        }
    }
}
