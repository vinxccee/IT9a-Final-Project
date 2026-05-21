<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceService extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'service_id',
        'quantity',
        'unit_price',
        'total_price',
        'service_date',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'service_date' => 'date',
    ];

    public function invoice() {
        return $this->belongsTo(Invoice::class);
    }

    public function service() {
        return $this->belongsTo(Service::class);
    }

    public function service() {
        return $this->belongsTo(Service::class);
    }
}
