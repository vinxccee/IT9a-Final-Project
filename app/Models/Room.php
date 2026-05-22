<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Room extends Model {
    use HasFactory;

    private const FALLBACK_IMAGES = [
        'standard' => 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=1200&q=80',
        'deluxe' => 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=1200&q=80',
        'suite' => 'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=1200&q=80',
        'default' => 'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=1200&q=80',
    ];

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

    public function getImageUrlAttribute(): string {
        if (! $this->image) {
            return $this->fallbackImageUrl();
        }

        if (Str::startsWith($this->image, ['http://', 'https://', 'data:'])) {
            return $this->image;
        }

        if (Storage::disk('public')->exists($this->image)) {
            return Storage::disk('public')->url($this->image);
        }

        return $this->fallbackImageUrl();
    }

    private function fallbackImageUrl(): string {
        $roomType = Str::lower((string) $this->roomType?->name);

        return self::FALLBACK_IMAGES[$roomType] ?? self::FALLBACK_IMAGES['default'];
    }
}
