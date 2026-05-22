<?php

namespace App\Providers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        $this->ensureGuestsSoftDeletesColumnExists();
    }

    private function ensureGuestsSoftDeletesColumnExists(): void
    {
        try {
            if (Schema::hasTable('guests') && ! Schema::hasColumn('guests', 'deleted_at')) {
                Schema::table('guests', function (Blueprint $table) {
                    $table->softDeletes()->after('updated_at');
                });
            }
        } catch (Throwable) {
            //
        }
    }
}
