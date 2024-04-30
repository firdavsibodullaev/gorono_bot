<?php

namespace App\Console\Commands;

use App\Jobs\SendPostToBotUsersJob;
use App\Models\PostMessage;
use Illuminate\Console\Command;

class ResendNotSentPost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:resend-not-sent-post';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        PostMessage::query()
            ->where('is_is_sent', false)
            ->each(function (PostMessage $postMessage) {
                SendPostToBotUsersJob::dispatch($postMessage->id);
            });
    }
}
