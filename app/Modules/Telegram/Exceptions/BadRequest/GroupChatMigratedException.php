<?php

namespace App\Modules\Telegram\Exceptions\BadRequest;

use App\Modules\Telegram\Exceptions\BadRequestException;

class GroupChatMigratedException extends BadRequestException
{
    protected int $migrate_to_chat_id;

    public function setMigrateToChatId(int $migrate_to_chat_id): static
    {
        $this->migrate_to_chat_id = $migrate_to_chat_id;

        return $this;
    }

    public function getMigrateToChatId(): int
    {
        return $this->migrate_to_chat_id;
    }
}
