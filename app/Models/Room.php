<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model {
    use HasFactory;

    protected $fillable = [
        'room_number', 'type', 'description',
        'price_per_night', 'capacity', 'status', 'image'
    ];

    public function bookings() {
        return $this->hasMany(Booking::class);
    }

    public function getTypeLabelAttribute(): string {
        return ucfirst($this->type);
    }

    public function getStatusColorAttribute(): string {
        return match($this->status) {
            'available'   => 'success',
            'occupied'    => 'danger',
            'maintenance' => 'warning',
            default       => 'secondary',
        };
    }
}