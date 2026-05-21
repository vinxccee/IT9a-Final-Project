<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('guests', function (Blueprint $table) {
            $table->enum('status', ['regular', 'vip', 'blacklisted'])->default('regular')->after('id_number');
            $table->string('preferred_room_type')->nullable()->after('status');
            $table->unsignedInteger('loyalty_points')->default(0)->after('preferred_room_type');
            $table->text('notes')->nullable()->after('loyalty_points');
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn(['status', 'preferred_room_type', 'loyalty_points', 'notes']);
        });
    }
};
