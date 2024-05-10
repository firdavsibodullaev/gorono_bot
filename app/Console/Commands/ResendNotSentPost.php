<?php

namespace App\Console\Commands;

use App\Jobs\PostMessageChain;
use App\Models\PostMessage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

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
    public function handle(): void
    {
        Artisan::call('queue:clear --queue=telegram-post');
        PostMessage::query()
            ->where('is_sent', false)
            ->each(function (PostMessage $postMessage) {
                PostMessageChain::dispatch($postMessage->id)->onQueue('telegram-post');
            });
    }
}
