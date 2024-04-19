<?php

namespace App\Actions\BotUser;

use App\Actions\BaseAction;
use App\DTOs\BotUser\BotUserUpdateLangDTO;
use App\Exceptions\WrongInstanceException;
use App\Models\BotUser;

/**
 * @property-read  BotUserUpdateLangDTO $payload
 */
class BotUserUpdateLangAction extends BaseAction
{
    /**
     * @throws WrongInstanceException
     */
    public function run(): BotUser
    {
        $this->isInstance(BotUserUpdateLangDTO::class);

        /** @var BotUser|null $bot_user */
        $this->payload->user->update((array)$this->payload);

        return $this->payload->user;
    }
}
