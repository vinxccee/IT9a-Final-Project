<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guest extends Model {
    use HasFactory;

    protected $fillable = [
        'first_name', 'last_name', 'email',
        'phone', 'address', 'id_type', 'id_number'
    ];

    public function bookings() {
        return $this->hasMany(Booking::class);
    }

    public function getFullNameAttribute(): string {
        return "{$this->first_name} {$this->last_name}";
    }
}