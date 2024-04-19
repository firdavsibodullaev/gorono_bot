<?php

namespace App\Console\Commands;

use App\Modules\Telegram\DTOs\Request\GetUpdatesDTO;
use App\Modules\Telegram\DTOs\Response\UpdateDTO;
use App\Modules\Telegram\Request;
use App\Telegram\BotInit;
use Illuminate\Console\Command;

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

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /** @var Request $api */
        $api = app(Request::class);

        $updates = $api->getUpdates();

        $update_id = $updates->result->last()?->update_id;

        loop:
        $api->getUpdates(new GetUpdatesDTO(offset: $update_id))
            ->result
            ->each(function (UpdateDTO $update) use (&$update_id) {

                $bot = new BotInit($update);
                $bot->index();

                $update_id = $update->update_id + 1;
            });

        goto loop;
    }
}
