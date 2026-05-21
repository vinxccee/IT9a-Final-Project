<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guest extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name', 'last_name', 'email',
        'phone', 'address', 'id_type', 'id_number',
        'status', 'preferred_room_type', 'loyalty_points', 'notes',
    ];

    protected $casts = [
        'loyalty_points' => 'integer',
    ];

    public function bookings() {
        return $this->hasMany(Booking::class);
    }

    public function checkins() {
        return $this->hasMany(CheckIn::class);
    }

    public function checkouts() {
        return $this->hasMany(CheckOut::class);
    }

    public function invoices() {
        return $this->hasMany(Invoice::class);
    }

    public function scopeSearch($query, ?string $search) {
        $search = trim((string) $search);

        if ($search === '') {
            return $query;
        }

        $terms = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);

        return $query->where(function ($innerQuery) use ($search, $terms) {
            $innerQuery->where('id', $search)
                ->orWhere('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhereHas('bookings', fn ($bookingQuery) => $bookingQuery->where('id', $search));

            if (count($terms) > 1) {
                $innerQuery->orWhere(function ($nameQuery) use ($terms) {
                    foreach ($terms as $term) {
                        $nameQuery->where(function ($termQuery) use ($term) {
                            $termQuery->where('first_name', 'like', "%{$term}%")
                                ->orWhere('last_name', 'like', "%{$term}%");
                        });
                    }
                });
            }
        });
    }

    public function getFullNameAttribute(): string {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getStatusColorAttribute(): string {
        return match ($this->status) {
            'vip' => 'warning',
            'blacklisted' => 'danger',
            default => 'info',
        };
    }

    public function getStatusLabelAttribute(): string {
        return str($this->status ?? 'regular')->replace('_', ' ')->title()->toString();
    }
}
