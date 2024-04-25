<?php

namespace App\Providers;

use App\Models\BotUser;
use App\Models\User;
use App\Observers\BotUserObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        BotUser::observe(BotUserObserver::class);

        Gate::define('viewLogViewer', function (?User $user) {
            if ($user === null) {
                return false;
            }

            if ($user->username === 'admin') {
                return true;
            }

            return false;
        });
    }
}
