<?php

namespace App\Console\Commands;

use App\Modules\Telegram\Facades\Request;
use Illuminate\Console\Command;

class BotSetWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:bot-set-webhook {--secret_token= : Секретный ключ для телеграм бота}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $url = $this->getUrl();

        $secret_token = $this->option('secret_token');

        Request::setWebhook($url, secret_token: $secret_token);
    }

    private function getUrl(): string
    {
        return sprintf("%s/%s",
            config('app.url'),
            'api/telegram-bot-connect'
        );
    }
}
