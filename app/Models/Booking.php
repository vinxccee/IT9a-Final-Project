<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model {
    use HasFactory;

    protected $fillable = [
        'room_id', 'guest_id', 'user_id',
        'check_in', 'check_out', 'num_guests',
        'total_amount', 'status', 'special_requests'
    ];

    protected $casts = [
        'check_in'  => 'date',
        'check_out' => 'date',
    ];

    public function room() {
        return $this->belongsTo(Room::class);
    }

    public function guest() {
        return $this->belongsTo(Guest::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getNightsAttribute(): int {
        return $this->check_in->diffInDays($this->check_out);
    }

    public function getStatusColorAttribute(): string {
        return match($this->status) {
            'pending'     => 'warning',
            'confirmed'   => 'info',
            'checked_in'  => 'success',
            'checked_out' => 'secondary',
            'cancelled'   => 'danger',
            default       => 'secondary',
        };
    }
}