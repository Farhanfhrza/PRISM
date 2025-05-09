<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Stationery;  
use App\Models\Requests;  
use App\Models\Request_detail;  
use App\Observers\StationeryObserver;  
use App\Observers\RequestObserver;  
use App\Observers\RequestDetailObserver;  
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
        Stationery::observe(StationeryObserver::class);
        Requests::observe(RequestObserver::class); 
        Request_detail::observe(RequestDetailObserver::class); 
    }
}
