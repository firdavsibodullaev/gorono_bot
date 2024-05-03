<?php

namespace App\Jobs;

use App\Models\BotUserPostMessage;
use App\Modules\Telegram\Enums\ChatMemberStatus;
use App\Modules\Telegram\Exceptions\BadRequestException;
use App\Modules\Telegram\Facades\Request;
use App\Telegram\Keyboard;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPostToBotUsersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;
    public int $backoff = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $post_id)
    {
        $this->delay(60);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $start_time = now();
        BotUserPostMessage::query()
            ->where('post_message_id', $this->post_id)
            ->where('is_sent', false)
            ->whereHas('botUser')
            ->with(['botUser', 'postMessage.creator'])
            ->lazy(100)
            ->each(function (BotUserPostMessage $postMessage, int $key) use (&$start_time) {
                loop:
                $post = $postMessage->postMessage;
                $botUser = $postMessage->botUser;

                try {
                    if (is_null($post->file_ids)) {
                        $message = Request::sendMessage($botUser->chat_id, $post->text, reply_markup: Keyboard::postMessageApprove());
                    } elseif ($post->file_ids['type'] == 'photo') {
                        $message = Request::sendPhoto(
                            $botUser->chat_id,
                            $post->file_ids['file_id'],
                            $post->text,
                            null,
                            $post->entities,
                            reply_markup: Keyboard::postMessageApprove()
                        );
                    } else {
                        return;
                    }
                } catch (BadRequestException $e) {
                    report($e);
                    if (in_array($e->getMessage(), ['Forbidden: user is deactivated', 'Forbidden: bot was blocked by the user'])) {
                        $botUser->update(['status' => ChatMemberStatus::Kicked]);
                    } elseif ($e->getCode() === 429) {
                        $sleepTime = (int)str($e->getMessage())->remove("Too Many Requests: retry after ")->toString();
                        sleep($sleepTime);
                        goto loop;
                    }
                    sleep(2);
                    return;
                }

                $postMessage->update(['is_sent' => true, 'sent_at' => now(), 'message_id' => $message->result->message_id]);


                if (BotUserPostMessage::query()
                        ->where('post_message_id', $this->post_id)
                        ->whereRelation('botUser', 'status', '=', ChatMemberStatus::Member)
                        ->where('is_sent', false)
                        ->count() === 0
                ) {

                    Request::sendMessage($post->creator->chat_id, __('Xabar yuborildi'));

                    $post->update(['is_sent' => true]);
                }


                if ($key % 10 === 0 && $key !== 0 || $start_time->diffInSeconds(now()) >= 60) {
                    $this->sendProgressToCreator($postMessage);
                    $start_time = now();
                    sleep(2);
                }
            });
    }

    private function sendProgressToCreator(BotUserPostMessage $message): void
    {

        $all_count = BotUserPostMessage::query()
            ->where('post_message_id', $this->post_id)
            ->count();

        $sent_count = BotUserPostMessage::query()
            ->where('post_message_id', $this->post_id)
            ->where('is_sent', true)
            ->count();

        $percent = (int)floor($sent_count / $all_count * 100);

        try {
            Request::editMessageText($message->postMessage->creator->chat_id, $message->postMessage->progress_message_id, "$sent_count/$all_count\n\n$percent%");
        } catch (BadRequestException $e) {

            if ($e->getMessage() === "Bad Request: message can't be edited") {
                $progressMessage = Request::sendMessage($message->postMessage->creator->chat_id, "$sent_count/$all_count\n\n$percent%");

                $message->postMessage->progress_message_id = $progressMessage->result->message_id;
                $message->postMessage->save();

                return;
            }

            report($e);
            if ($e->getCode() === 429) {
                $sleepTime = (int)str($e->getMessage())->remove("Too Many Requests: retry after ")->toString();
                sleep($sleepTime);
            }
            sleep(2);
        }
    }
}
