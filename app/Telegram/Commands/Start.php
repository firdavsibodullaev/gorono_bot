<?php

namespace App\Telegram\Commands;

use App\Actions\BotUser\BotUserByFromIdChatIdAction;
use App\Models\BotUser;
use App\Modules\Telegram\DTOs\Response\MessageDTO;
use App\Telegram\Action\Action;
use App\Telegram\Update\Message\Private\Registration;
use App\Telegram\Update\Message\Private\SendMainMessage;

class Start
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
        (new Action($this->from_id, $this->chat_id))->clear();

        if (!$this->user->is_registered) {
            (new Registration($this->message))->index();
            return;
        }

        SendMainMessage::send($this->from_id, $this->chat_id);
    }
}
