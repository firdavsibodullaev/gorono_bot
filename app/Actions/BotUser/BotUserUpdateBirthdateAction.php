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

        /** @var BotUser|null $bot_user */

        $this->payload->user->update((array)$this->payload);

        return $this->payload->user;
    }
}
