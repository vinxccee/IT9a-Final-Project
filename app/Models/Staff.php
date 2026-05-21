<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Staff extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id', 'position', 'department',
        'phone', 'hired_at', 'status'
    ];

    protected $casts = ['hired_at' => 'date'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function housekeepingTasks() {
        return $this->hasMany(HousekeepingTask::class);
    }
}