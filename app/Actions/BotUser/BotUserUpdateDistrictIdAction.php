<?php

namespace App\Actions\BotUser;

use App\Actions\BaseAction;
use App\DTOs\BotUser\BotUserUpdateDistrictIdDTO;
use App\Exceptions\WrongInstanceException;
use App\Models\BotUser;

/**
 * @property-read  BotUserUpdateDistrictIdDTO $payload
 */
class BotUserUpdateDistrictIdAction extends BaseAction
{
    /**
     * @throws WrongInstanceException
     */
    public function run(): BotUser
    {
        $this->isInstance(BotUserUpdateDistrictIdDTO::class);

        /** @var BotUser|null $bot_user */

        $this->payload->user->update((array)$this->payload);

        return $this->payload->user;
    }
}
