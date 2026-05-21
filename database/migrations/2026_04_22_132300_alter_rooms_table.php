<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('price_per_night');
            $table->dropColumn('capacity');
            $table->foreignId('room_type_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->enum('type', ['standard', 'deluxe', 'suite', 'presidential']);
            $table->decimal('price_per_night', 10, 2);
            $table->integer('capacity');
            $table->dropForeign(['room_type_id']);
            $table->dropColumn('room_type_id');
        });
    }
};