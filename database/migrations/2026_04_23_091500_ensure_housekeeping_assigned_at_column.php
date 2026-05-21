<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('housekeeping_tasks', 'assigned_at')) {
            Schema::table('housekeeping_tasks', function (Blueprint $table) {
                $table->dateTime('assigned_at')->nullable()->after('status');
            });
        }

        DB::table('housekeeping_tasks')
            ->whereNull('assigned_at')
            ->update(['assigned_at' => DB::raw('created_at')]);
    }

    public function down(): void
    {
        if (Schema::hasColumn('housekeeping_tasks', 'assigned_at')) {
            Schema::table('housekeeping_tasks', function (Blueprint $table) {
                $table->dropColumn('assigned_at');
            });
        }
    }
};
