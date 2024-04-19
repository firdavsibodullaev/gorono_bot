<?php

namespace App\Actions\BotUser;

use App\Actions\BaseAction;
use App\DTOs\BotUser\BotUserUpdatePhoneDTO;
use App\Exceptions\WrongInstanceException;
use App\Models\BotUser;

/**
 * @property-read  BotUserUpdatePhoneDTO $payload
 */
class BotUserUpdatePhoneAction extends BaseAction
{
    /**
     * @throws WrongInstanceException
     */
    public function run(): BotUser
    {
        $this->isInstance(BotUserUpdatePhoneDTO::class);

        /** @var BotUser|null $bot_user */

        $this->payload->user->update((array)$this->payload);

        return $this->payload->user;
    }
}
