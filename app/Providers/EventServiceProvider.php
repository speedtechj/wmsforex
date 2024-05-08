<?php

namespace App\Providers;

use App\Models\Skiddinginfo;
use App\Observers\SkiddinginfoObserver;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        
        Skiddinginfo::observe(SkiddinginfoObserver::class);
    }
}
