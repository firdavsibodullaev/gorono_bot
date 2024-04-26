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
    protected $signature = 'app:bot-set-webhook
                            {--secret_token= : Секретный ключ для телеграм бота}
                            {--remove= : Удаление вебхука}';

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

        $secret_token = $this->option('secret_token');

        $delete = (bool)$this->option('remove');

        $url = !$delete ? $this->getUrl() : "";

        $response = Request::setWebhook($url, secret_token: $secret_token);

        $response->ok
            ? $this->components->info($response->description)
            : $this->components->error($response->description);
    }

    private function getUrl(): string
    {
        return sprintf("%s/%s",
            config('app.url'),
            'api/telegram-bot-connect'
        );
    }
}
