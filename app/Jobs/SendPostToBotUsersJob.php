<?php

namespace App\Jobs;

use App\Enums\BotUserPostMessageStatus;
use App\Models\BotUser;
use App\Models\BotUserPostMessage;
use App\Modules\Telegram\Enums\ChatMemberStatus;
use App\Modules\Telegram\Exceptions\BadRequest\MessageCantBeEditedException;
use App\Modules\Telegram\Exceptions\BadRequestException;
use App\Modules\Telegram\Exceptions\BaseException;
use App\Modules\Telegram\Exceptions\ForbiddenException;
use App\Modules\Telegram\Exceptions\TooManyTimesException;
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

    public int $tries = 20;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $post_id)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $start_time = now();
        BotUserPostMessage::query()
            ->where('post_message_id', $this->post_id)
            ->where('status', BotUserPostMessageStatus::Process)
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

                    $this->checkBotUserStatus($botUser);

                } catch (TooManyTimesException $e) {
                    $this->handleTooManyTimes($e);
                    goto loop;
                } catch (BadRequestException $e) {
                    $this->handleBadRequest($e, $postMessage);
                    return;
                } catch (ForbiddenException $e) {
                    $this->handleForbidden($e, $postMessage, $botUser);
                    return;
                } catch (BaseException $e) {
                    report($e);
                    return;
                }

                $postMessage->update(['status' => BotUserPostMessageStatus::Success, 'sent_at' => now(), 'message_id' => $message->result->message_id]);

                if (BotUserPostMessage::query()
                        ->where('post_message_id', $this->post_id)
                        ->where('status', BotUserPostMessageStatus::Process)
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
            ->where('status', '!=', BotUserPostMessageStatus::Process)
            ->count();

        $percent = (int)floor($sent_count / $all_count * 100);
        $time = now()->format('d.m.Y H:i:s');
        $progress_text = "$sent_count/$all_count\n\n$percent%\n\n\nVaqti: $time";

        try {
            Request::editMessageText($message->postMessage->creator->chat_id, $message->postMessage->progress_message_id, $progress_text);
        } catch (MessageCantBeEditedException) {
            $progressMessage = Request::sendMessage($message->postMessage->creator->chat_id, $progress_text);
            $message->postMessage->progress_message_id = $progressMessage->result->message_id;
            $message->postMessage->save();
        } catch (TooManyTimesException $e) {
            report($e);
            sleep($e->getRetryAfter());
        } catch (BaseException $e) {
            report($e);
            sleep(2);
        }
    }

    private function checkBotUserStatus(BotUser $botUser): void
    {
        if ($botUser->status->is(ChatMemberStatus::Member)) {
            return;
        }

        $botUser->update(['status' => ChatMemberStatus::Member]);
    }

    private function handleBadRequest(BadRequestException $e, BotUserPostMessage $postMessage): void
    {
        report($e);
        $postMessage->update(['status' => BotUserPostMessageStatus::Fail]);
        sleep(2);
    }

    private function handleForbidden(ForbiddenException $e, BotUserPostMessage $postMessage, BotUser $botUser): void
    {
        report($e);
        $postMessage->update(['status' => BotUserPostMessageStatus::Fail]);
        $botUser->update(['status' => ChatMemberStatus::Kicked]);

    }

    private function handleTooManyTimes(TooManyTimesException $e): void
    {
        report($e);
        sleep($e->getRetryAfter());
    }
}
