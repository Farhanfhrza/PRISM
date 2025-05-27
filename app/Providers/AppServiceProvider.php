<?php

namespace App\Providers;

use App\Models\Requests;  
use App\Models\InsertStock;
use App\Models\Stationery;  
use App\Models\Request_detail;  
use App\Observers\RequestObserver;  
use App\Observers\InsertStockObserver;
use App\Observers\StationeryObserver;  
use Illuminate\Support\ServiceProvider;
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
        InsertStock::observe(InsertStockObserver::class); 
    }
}
