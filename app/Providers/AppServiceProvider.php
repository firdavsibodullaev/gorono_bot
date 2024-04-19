<?php

namespace App\Providers;

use App\Modules\Telegram\Api;
use App\Modules\Telegram\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('telegram.request', fn($app) => new Request(new Api));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
