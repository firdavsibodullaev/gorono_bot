<?php

namespace App\Console\Commands;

use App\Exceptions\UpdateNotPermittedException;
use App\Modules\Telegram\DTOs\Response\UpdateDTO;
use App\Modules\Telegram\Facades\Request;
use App\Telegram\BotInit;
use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Log;
use Throwable;

class RunBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:bot-run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run bot development';

    protected int $requests_count = 1;

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->components->info("Bot started...");

        $this->notifyAboutBotStart();

        $updates = retry(3, fn() => Request::getUpdates());

        $update_id = $updates->result->last()?->update_id;

        loop:
        try {
            Request::getUpdates(offset: $update_id)
                ->result
                ->each(function (UpdateDTO $update) use (&$update_id) {
                    $this->components->info(now()->toDateTimeString() . " Request...");
                    $bot = new BotInit($update);
                    $bot->index();

                    $update_id = $update->update_id + 1;
                });

            $this->sleepIfNecessary();

        } catch (ConnectionException|UpdateNotPermittedException) {
            sleep(1);
        } catch (Throwable $e) {
            $this->components->error("Error: " . $e->getMessage());
            sleep(1);
            Log::channel('daily')->error("Telegram error", [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
        goto loop;
    }

    private function sleepIfNecessary(): void
    {
        $this->requests_count++;

        if ($this->requests_count % 10 == 0) {
            sleep(1);
        }
    }

    private function notifyAboutBotStart(): void
    {
        $admins = config('services.telegram.admin');

        foreach ($admins as $chat_id) {
            Request::sendMessage($chat_id, "Bot ishga tushdi\n\nVaqt: " . date('Y-m-d H:i:s'));
        }

    }
}
