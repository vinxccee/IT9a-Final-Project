<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_number')->unique();
            $table->enum('type', ['standard', 'deluxe', 'suite', 'presidential']);
            $table->text('description')->nullable();
            $table->decimal('price_per_night', 10, 2);
            $table->integer('capacity');
            $table->enum('status', ['available', 'occupied', 'maintenance'])->default('available');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('rooms');
    }
};