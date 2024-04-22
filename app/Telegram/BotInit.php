<?php

namespace App\Telegram;

use App\Exceptions\StopExecutionException;
use App\Modules\Telegram\DTOs\Response\UpdateDTO;
use App\Telegram\Update\Message\PrivateMessage;
use App\Telegram\Update\MyChatMember;

class BotInit
{
    public function __construct(protected UpdateDTO $update)
    {
    }

    public function index(): void
    {
        try {
            if ($this->update->message) {
                PrivateMessage::make($this->update)->index();
            } elseif ($this->update->my_chat_member) {
                MyChatMember::make($this->update)->index();
            }
        } catch (StopExecutionException) {
        }
    }
}
