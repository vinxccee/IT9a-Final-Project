<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CheckIn extends Model
{
    use HasFactory;

    protected $table = 'checkins';

    protected $fillable = [
        'booking_id', 'room_id', 'guest_id', 'staff_id',
        'checkin_time', 'notes', 'id_document_type', 'id_document_number'
    ];

    protected $casts = [
        'checkin_time' => 'datetime',
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
