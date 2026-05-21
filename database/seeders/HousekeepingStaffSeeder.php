<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HousekeepingStaffSeeder extends Seeder
{
    public function run(): void
    {
        $role = UserRole::where('name', 'housekeeping_staff')->first();

        if ($role) {
            User::updateOrCreate(
                ['email' => 'housekeeping@hotel.com'],
                [
                    'name' => 'Housekeeping Staff',
                    'password' => Hash::make('password'),
                    'user_role_id' => $role->id,
                    'phone' => '555-0101',
                    'is_active' => true
                ]
            );
        }
    }
}