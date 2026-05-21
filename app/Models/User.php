<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'user_role_id', 'phone', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function getIsActiveAttribute($value): bool {
        if ($value !== null) {
            return (bool) $value;
        }

        return ($this->attributes['status'] ?? 'active') !== 'inactive';
    }

    public function userRole() {
        return $this->belongsTo(UserRole::class);
    }

    public function hasRole(string|array $roles): bool {
        $roleName = $this->userRole?->name;

        if ($roleName === null) {
            return false;
        }

        return in_array($roleName, (array) $roles, true);
    }

    public function isAdmin(): bool {
        return $this->hasRole('admin');
    }

    public function isReceptionist(): bool {
        return $this->hasRole('receptionist');
    }

    public function isGuest(): bool {
        return $this->hasRole('guest');
    }

    public function isHousekeepingStaff(): bool {
        return $this->hasRole('housekeeping_staff');
    }

    public function isStaffOrAdmin(): bool {
        return $this->hasRole(['receptionist', 'admin', 'housekeeping_staff']);
    }

    public function isStaff(): bool {
        return $this->hasRole(['receptionist', 'admin', 'housekeeping_staff']);
    }

    public function getRoleAttribute(): string {
        return $this->userRole?->name ?? 'unassigned';
    }

    public function roleLabel(): string {
        return str($this->userRole?->name ?? 'unassigned')->replace('_', ' ')->title()->toString();
    }

    public function canAccessModule(string $module): bool {
        return match ($module) {
            'room_management' => $this->hasRole(['guest', 'receptionist', 'admin']),
            'reservation_booking' => $this->hasRole(['guest', 'receptionist', 'admin']),
            'checkin_checkout' => $this->hasRole(['receptionist', 'admin']),
            'billing_payments' => $this->hasRole(['guest', 'receptionist', 'admin']),
            'service_requests' => $this->hasRole(['guest', 'receptionist', 'admin']),
            'housekeeping_maintenance' => $this->hasRole(['receptionist', 'admin', 'housekeeping_staff']),
            'guest_history' => $this->hasRole(['receptionist', 'admin']),
            'reports_analytics' => $this->hasRole(['receptionist', 'admin']),
            'admin_panel' => $this->isAdmin(),
            default => false,
        };
    }

    public function bookings() {
        return $this->hasMany(Booking::class);
    }

    public function staff() {
        return $this->hasOne(Staff::class);
    }
}
