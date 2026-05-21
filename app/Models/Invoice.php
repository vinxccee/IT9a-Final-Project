<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id', 'guest_id', 'room_charge', 'service_charge',
        'total_amount', 'paid_amount', 'status', 'due_date', 'notes'
    ];

    protected $casts = [
        'room_charge' => 'decimal:2',
        'service_charge' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_date' => 'date',
    ];

    public function booking() {
        return $this->belongsTo(Booking::class);
    }

    public function guest() {
        return $this->belongsTo(Guest::class);
    }

    public function payments() {
        return $this->hasMany(Payment::class);
    }

    public function invoiceServices() {
        return $this->hasMany(InvoiceService::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getGuestRecordAttribute(): ?Guest {
        return $this->relationLoaded('booking')
            ? $this->booking?->guest
            : $this->booking()->with('guest')->first()?->guest;
    }

    public function getDisplayNumberAttribute(): string {
        return $this->invoice_number ?: ('INV-' . str_pad((string) $this->id, 4, '0', STR_PAD_LEFT));
    }

    public function getBalanceAttribute(): float {
        return max(0, (float) $this->total_amount - (float) $this->paid_amount);
    }
}
