<?php

namespace App\Actions\BotUser;

use App\Actions\BaseAction;
use App\DTOs\BotUser\BotUserUpdateBirthdateDTO;
use App\Exceptions\WrongInstanceException;
use App\Models\BotUser;

/**
 * @property-read  BotUserUpdateBirthdateDTO $payload
 */
class BotUserUpdateBirthdateAction extends BaseAction
{
    /**
     * @throws WrongInstanceException
     */
    public function run(): BotUser
    {
        $this->isInstance(BotUserUpdateBirthdateDTO::class);

        $this->payload->user->update((array)$this->payload);

        cache()->put(
            key: "bot-user-{$this->payload->user->from_id}-{$this->payload->user->chat_id}",
            value: $this->payload->user,
            ttl: now()->addDay()
        );

        return $this->payload->user;
    }
}
