<?php

namespace App\Actions\BotUser;

use App\Actions\BaseAction;
use App\DTOs\BotUser\BotUserUpdateNameDTO;
use App\Exceptions\WrongInstanceException;
use App\Models\BotUser;

/**
 * @property-read  BotUserUpdateNameDTO $payload
 */
class BotUserUpdateNameAction extends BaseAction
{
    /**
     * @throws WrongInstanceException
     */
    public function run(): BotUser
    {
        $this->isInstance(BotUserUpdateNameDTO::class);

        /** @var BotUser|null $bot_user */

        $this->payload->user->update((array)$this->payload);

        return $this->payload->user;
    }
}
