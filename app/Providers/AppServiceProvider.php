<?php

namespace App\Providers;

use App\Models\Spk;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Observers\SPKObserver;

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
        Spk::observe(SPKObserver::class);
        Schema::defaultStringLength(191);
    }
}
