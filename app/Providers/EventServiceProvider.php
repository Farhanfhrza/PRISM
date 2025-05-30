<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Activity_log;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Listeners\LogLoginActivity;
use App\Listeners\LogLogoutActivity;

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        Login::class => [
            LogLoginActivity::class,
        ],
        Logout::class => [
            LogLogoutActivity::class,
        ],
    ];

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
