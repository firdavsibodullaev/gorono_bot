<?php

namespace App\Jobs;

use App\Enums\BotUserPostMessageStatus;
use App\Models\BotUserPostMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;

class PostMessageChain implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected int $post_message_id)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $bot_user_post_messages = BotUserPostMessage::query()
            ->orderBy('id')
            ->where('post_message_id', $this->post_message_id)
            ->where('status', BotUserPostMessageStatus::Process)
            ->whereHas('botUser')
            ->get(['id']);

        $chain = collect();

        $start_id = null;

        /** @var BotUserPostMessage $bot_user_post_message */
        foreach ($bot_user_post_messages as $key => $bot_user_post_message) {

            $start_id = $start_id ?: $bot_user_post_message->id;

            if ($key % 100 === 0 && $key !== 0 || $bot_user_post_messages->count() - 1 === $key) {
                $end_id = $bot_user_post_message->id;

                $chain->push(new SendPostToBotUsersJob($this->post_message_id, $start_id, $end_id));

                $start_id = null;
            }
        }

        if (empty($chain)) {
            return;
        }

        Bus::chain($chain)->onQueue('telegram-post')->dispatch();
    }
}
