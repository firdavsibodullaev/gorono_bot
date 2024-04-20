<?php

namespace App\Modules\Telegram;

use Illuminate\Support\ServiceProvider;

class TelegramBotServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('telegram.request', fn($app) => new Request(new Api));
    }
}
