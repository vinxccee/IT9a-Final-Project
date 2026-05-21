<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'is_active')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('user_role_id');
            });
        }

        if (Schema::hasColumn('users', 'status')) {
            DB::table('users')
                ->whereRaw('LOWER(COALESCE(status, "active")) = ?', ['inactive'])
                ->update(['is_active' => false]);

            DB::table('users')
                ->whereRaw('LOWER(COALESCE(status, "active")) <> ?', ['inactive'])
                ->update(['is_active' => true]);
        } else {
            DB::table('users')->whereNull('is_active')->update(['is_active' => true]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'is_active')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }
    }
};
