<?php

namespace App\Actions\BotUser;

use App\Actions\BaseAction;
use App\DTOs\BotUser\BotUserCreateDTO;
use App\Exceptions\WrongInstanceException;
use App\Models\BotUser;

/**
 * @property-read  BotUserCreateDTO $payload
 */
class BotUserCreateAction extends BaseAction
{
    /**
     * @throws WrongInstanceException
     */
    public function run(): BotUser
    {
        $this->isInstance(BotUserCreateDTO::class);

        /** @var BotUser|null $bot_user */
        $bot_user = BotUser::query()->create((array)$this->payload);

        cache()->put(
            key: "bot-user-{$this->payload->from_id}-{$this->payload->chat_id}",
            value: $bot_user,
            ttl: now()->addDay()
        );

        return $bot_user;
    }
}
