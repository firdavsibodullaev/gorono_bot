<?php

namespace App\Actions\BotUser;

use App\Actions\BaseAction;
use App\DTOs\BotUser\BotUserUpdateSchoolIdDTO;
use App\Exceptions\WrongInstanceException;
use App\Models\BotUser;

/**
 * @property-read  BotUserUpdateSchoolIdDTO $payload
 */
class BotUserUpdateSchoolIdAction extends BaseAction
{
    /**
     * @throws WrongInstanceException
     */
    public function run(): BotUser
    {
        $this->isInstance(BotUserUpdateSchoolIdDTO::class);

        /** @var BotUser|null $bot_user */

        $payload = array_filter((array)$this->payload, fn($data) => !is_null($data));

        $this->payload->user->update($payload);

        return $this->payload->user;
    }
}
