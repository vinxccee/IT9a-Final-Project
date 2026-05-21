<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'guest',
                'description' => 'Registers an account, browses available rooms, makes/cancels reservations, requests services, makes payments, and leaves feedback.'
            ],
            [
                'name' => 'receptionist',
                'description' => 'Manages check-ins/check-outs, guest reservations, room status, billing, payments, housekeeping tasks, and walk-in bookings.'
            ],
            [
                'name' => 'housekeeping_staff',
                'description' => 'Handles room cleaning, maintenance tasks, restocking supplies, and updates task status with notes.'
            ],
            [
                'name' => 'admin',
                'description' => 'Full system access — manages users, rooms, pricing, employees, reports, analytics, system settings, and overrides.'
            ],
        ];

        foreach ($roles as $role) {
            \App\Models\UserRole::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
