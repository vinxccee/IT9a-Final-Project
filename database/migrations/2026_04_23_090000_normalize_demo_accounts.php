<?php

use App\Models\Staff;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $roles = UserRole::query()->pluck('id', 'name');
        $hasStatusColumn = Schema::hasColumn('users', 'status');

        $demoUsers = [
            [
                'name' => 'Hotel Admin',
                'email' => 'admin@hotel.com',
                'role' => 'admin',
                'phone' => '09090000001',
                'staff' => [
                    'position' => 'General Manager',
                    'department' => 'Administration',
                    'phone' => '09101234560',
                    'hired_at' => '2022-01-01',
                    'status' => 'active',
                ],
            ],
            [
                'name' => 'Jane Reception',
                'email' => 'reception@hotel.com',
                'legacy_email' => 'staff@hotel.com',
                'role' => 'receptionist',
                'phone' => '09090000002',
                'staff' => [
                    'position' => 'Front Desk Officer',
                    'department' => 'Reception',
                    'phone' => '09101234567',
                    'hired_at' => '2023-01-15',
                    'status' => 'active',
                ],
            ],
            [
                'name' => 'Marco Operations',
                'email' => 'operations@hotel.com',
                'role' => 'receptionist',
                'phone' => '09090000003',
                'staff' => [
                    'position' => 'Operations Officer',
                    'department' => 'Operations',
                    'phone' => '09101234568',
                    'hired_at' => '2023-06-12',
                    'status' => 'active',
                ],
            ],
            [
                'name' => 'John Guest',
                'email' => 'guest@hotel.com',
                'role' => 'guest',
                'phone' => '09090000004',
                'staff' => null,
            ],
        ];

        foreach ($demoUsers as $demoUser) {
            $user = User::query()
                ->where('email', $demoUser['email'])
                ->when(isset($demoUser['legacy_email']), fn ($query) => $query->orWhere('email', $demoUser['legacy_email']))
                ->first();

            if ($user) {
                $user->update([
                    'name' => $demoUser['name'],
                    'email' => $demoUser['email'],
                    'user_role_id' => $roles[$demoUser['role']] ?? null,
                    'phone' => $demoUser['phone'],
                    'is_active' => true,
                ]);
            } else {
                $payload = [
                    'name' => $demoUser['name'],
                    'email' => $demoUser['email'],
                    'password' => Hash::make('password'),
                    'user_role_id' => $roles[$demoUser['role']] ?? null,
                    'phone' => $demoUser['phone'],
                    'is_active' => true,
                ];

                if ($hasStatusColumn) {
                    $payload['status'] = 'active';
                    $userId = DB::table('users')->insertGetId($payload);
                    $user = User::query()->find($userId);
                } else {
                    $user = User::query()->create($payload);
                }
            }

            if ($demoUser['staff']) {
                Staff::query()->updateOrCreate(
                    ['user_id' => $user->id],
                    $demoUser['staff']
                );
            }
        }
    }

    public function down(): void
    {
        //
    }
};
