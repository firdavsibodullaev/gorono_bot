<?php

namespace App\Telegram\Commands;

use App\Actions\BotUser\BotUserByFromIdChatIdAction;
use App\Enums\Method;
use App\Models\BotUser;
use App\Modules\Telegram\DTOs\Response\MessageDTO;
use App\Telegram\Action\Action;
use App\Telegram\Keyboard;
use App\Telegram\Update\Message\Private\PostMessage;

class Post
{
    public int $from_id;
    public int $chat_id;
    protected ?BotUser $user;

    public function __construct(protected MessageDTO $message)
    {
        $this->from_id = $this->message->from->id;
        $this->chat_id = $this->message->chat->id;
        $this->user = BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run();
    }

    public function __invoke(): void
    {
        Action::make($this->from_id, $this->chat_id)->set(PostMessage::class, Method::GetPostMessage);

        $this->message->sendMessage(__('Xabarni kiriting'), reply_markup: Keyboard::back());
    }
}
