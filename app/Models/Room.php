<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Room extends Model {
    use HasFactory;

    protected $fillable = [
        'room_number', 'room_type_id', 'description', 'status', 'image'
    ];

    protected $appends = ['type', 'price_per_night', 'capacity'];

    public function roomType() {
        return $this->belongsTo(RoomType::class);
    }

    public function bookings() {
        return $this->hasMany(Booking::class);
    }

    public function housekeepingTasks() {
        return $this->hasMany(HousekeepingTask::class);
    }

    public function getTypeLabelAttribute(): string {
        return $this->roomType ? ucfirst($this->roomType->name) : 'Unknown';
    }

    public function getTypeAttribute(): ?string {
        return $this->roomType?->name;
    }

    public function getPricePerNightAttribute(): float {
        return (float) ($this->roomType?->base_price ?? 0);
    }

    public function getCapacityAttribute(): int {
        return (int) ($this->roomType?->capacity ?? 0);
    }

    public function getStatusColorAttribute(): string {
        return match($this->status) {
            'available'   => 'success',
            'occupied'    => 'danger',
            'under_maintenance' => 'warning',
            'maintenance' => 'warning',
            default       => 'secondary',
        };
    }

    public function getImageUrlAttribute(): ?string {
        if (! $this->image) {
            return null;
        }

        if (Str::startsWith($this->image, ['http://', 'https://', 'data:'])) {
            return $this->image;
        }

        return Storage::disk('public')->url($this->image);
    }
}
