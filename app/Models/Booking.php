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
        'total_amount' => 'decimal:2',
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

    public function checkin() {
        return $this->hasOne(CheckIn::class);
    }

    public function checkout() {
        return $this->hasOne(CheckOut::class);
    }

    public function invoice() {
        return $this->hasOne(Invoice::class);
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

    public function scopeActive($query) {
        return $query->whereIn('status', ['pending', 'confirmed', 'checked_in']);
    }
}
