<?php

namespace App\Actions\BotUser;

use App\Actions\BaseAction;
use App\DTOs\BotUser\BotUserUpdateStatusDTO;
use App\Exceptions\WrongInstanceException;
use App\Models\BotUser;

/**
 * @property-read  BotUserUpdateStatusDTO $payload
 */
class BotUserUpdateStatusAction extends BaseAction
{
    /**
     * @throws WrongInstanceException
     */
    public function run(): BotUser
    {
        $this->isInstance(BotUserUpdateStatusDTO::class);

        /** @var BotUser|null $bot_user */

        $this->payload->user->update((array)$this->payload);

        return $this->payload->user;
    }
}
