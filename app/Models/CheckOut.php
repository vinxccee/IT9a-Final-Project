<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CheckOut extends Model
{
    use HasFactory;

    protected $table = 'checkouts';

    protected $fillable = [
        'booking_id', 'room_id', 'guest_id', 'staff_id',
        'checkout_time', 'total_amount', 'notes'
    ];

    protected $casts = [
        'checkout_time' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    public function booking() {
        return $this->belongsTo(Booking::class);
    }

    public function room() {
        return $this->belongsTo(Room::class);
    }

    public function guest() {
        return $this->belongsTo(Guest::class);
    }

    public function staff() {
        return $this->belongsTo(Staff::class);
    }
}
