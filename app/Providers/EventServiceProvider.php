<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Login;  
use Illuminate\Auth\Events\Logout;  
use App\Models\Activity_log;

class EventServiceProvider extends ServiceProvider
{

    // protected $listen = [  
    //     Login::class => [  
    //         function ($event) {  
    //             Activity_log::log(  
    //                 'authentication',  
    //                 'login',  
    //                 'authentication',  
    //             );  
    //         },  
    //     ],  
    //     Logout::class => [  
    //         function ($event) {  
    //             Activity_log::log(  
    //                 'authentication',  
    //                 'logout',  
    //                 'authentication'  
    //             );  
    //         },  
    //     ],  
    // ]; 
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
        //
    }
}
