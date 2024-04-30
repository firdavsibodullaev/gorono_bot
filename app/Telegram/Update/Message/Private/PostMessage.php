<?php

namespace App\Telegram\Update\Message\Private;

use App\Actions\BotUser\BotUserByFromIdChatIdAction;
use App\Enums\Method;
use App\Exceptions\StopExecutionException;
use App\Jobs\SendPostToBotUsersJob;
use App\Models\BotUser;
use App\Models\PostMessage as PostMessageModel;
use App\Modules\Telegram\DTOs\Response\MessageDTO;
use App\Modules\Telegram\DTOs\Response\PhotoDTO;
use App\Telegram\Action\Action;
use App\Telegram\BackAction;
use App\Telegram\BaseAction;
use App\Telegram\Keyboard;

class PostMessage extends BaseAction
{
    protected ?string $caption;
    protected BotUser $user;

    public function __construct(MessageDTO $message)
    {
        $this->method = Method::GetPostMessage;

        $this->caption = $message->caption;

        parent::__construct($message);

        $this->user = BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run();
    }

    /**
     * @throws StopExecutionException
     */
    public function getPostMessage(bool $is_back = false): void
    {
        if (!$is_back) {
            BackAction::back($this->text, $this->user, fn() => SendMainMessage::send($this->from_id, $this->chat_id));

        }

        if ($this->text && str($this->text)->length() > 4096) {
            $this->message->sendMessage(
                text: __('4096 tadan ko\'p belgi jo\'natish mumkin emas'),
                reply_markup: Keyboard::back()
            );
            return;
        }

        if ($this->caption && str($this->caption)->length() > 1024) {
            $this->message->sendMessage(
                text: __('1024 tadan ko\'p belgi jo\'natish mumkin emas'),
                reply_markup: Keyboard::back()
            );
            return;
        }

        PostMessageModel::query()
            ->where('is_ready_for_post', false)
            ->where('bot_user_id', $this->user->id)
            ->delete();

        /** @var PostMessageModel $post */
        $post = PostMessageModel::query()->create([
            'bot_user_id' => $this->user->id,
            'text' => $this->caption ?: $this->text,
            'file_ids' => $this->getFileId(),
            'entities' => $this->message->caption_entities,
            'is_ready_for_post' => false
        ]);

        if (is_null($post->file_ids)) {
            $this->message->sendMessage($post->text, reply_markup: Keyboard::postMessageApprove());
        } elseif ($post->file_ids['type'] == 'photo') {
            $this->message->sendPhoto(
                $post->file_ids['file_id'],
                $post->text,
                null,
                $post->entities,
                reply_markup: Keyboard::postMessageApprove()
            );
        } else {
            return;
        }

        $bot_users_count = BotUser::member()->count();

        $this->message->sendMessage(__('Foydalanuvchilar soni', ['count' => $bot_users_count]));

        $this->action->set(static::class, Method::ApprovePostMessage);
    }

    public function approvePostMessage(): void
    {
        BackAction::back($this->text, $this->user, function () {
            Action::make($this->from_id, $this->chat_id)->set(PostMessage::class, Method::GetPostMessage);
            $this->message->sendMessage(__('Xabarni kiriting'), reply_markup: Keyboard::back());
        });

        if ($this->text === __('Tasdiqlash')) {
            /** @var PostMessageModel $post */
            $post = PostMessageModel::query()
                ->where('is_ready_for_post', false)
                ->where('bot_user_id', $this->user->id)
                ->first();

            $post->update(['is_ready_for_post' => true]);

            SendPostToBotUsersJob::dispatch($post->id);
            $progress = $this->message->sendMessage("0%");

            $post->update(['progress_message_id' => $progress->result->message_id]);

            $post->botUsers()->sync(BotUser::member()->pluck('id'));
        }
    }

    private function getFileId(): ?array
    {
        /** @var PhotoDTO $photo */
        $photo = $this->message->photo?->last();

        if ($file_id = $photo?->file_id) {
            return ['type' => 'photo', 'file_id' => $file_id];
        }

        return null;
    }
}
