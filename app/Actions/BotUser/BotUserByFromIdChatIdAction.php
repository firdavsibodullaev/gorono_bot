<?php

namespace App\Actions\BotUser;

use App\Actions\BaseAction;
use App\DTOs\BotUser\BotUserByFromIdChatIdDTO;
use App\Exceptions\WrongInstanceException;
use App\Models\BotUser;

/**
 * @property-read  BotUserByFromIdChatIdDTO $payload
 */
class BotUserByFromIdChatIdAction extends BaseAction
{
    /**
     * @throws WrongInstanceException
     */
    public function run(): ?BotUser
    {
        $this->isInstance(BotUserByFromIdChatIdDTO::class);

        /** @var BotUser|null $bot_user */

        $bot_user = BotUser::query()
            ->where('from_id', $this->payload->from_id)
            ->where('chat_id', $this->payload->chat_id)
            ->first();

        return $bot_user;
    }
}
