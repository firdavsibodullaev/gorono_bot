<?php

namespace App\Telegram;

use App\Modules\Telegram\DTOs\Response\UpdateDTO;

class BotInit
{
    public function __construct(protected UpdateDTO $update)
    {
    }

    public function index(): void
    {
        if ($this->update->message) {
            Message::make($this->update)->index();
        } elseif ($this->update->my_chat_member) {
            MyChatMember::make($this->update)->index();
        }
    }
}
