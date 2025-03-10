<?php

namespace App\Providers;

use App\Console\Commands\ExpiredReservationsCleanup;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

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
        $this->commands([
            ExpiredReservationsCleanup::class,
        ]);
        Vite::prefetch(concurrency: 3);
    }
}
