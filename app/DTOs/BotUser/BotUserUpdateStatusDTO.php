<?php

namespace App\DTOs\BotUser;

use App\DTOs\BaseDTO;
use App\Models\BotUser;
use App\Modules\Telegram\Enums\ChatMemberStatus;

class BotUserUpdateStatusDTO extends BaseDTO
{
    public function __construct(public BotUser $user, public ChatMemberStatus $status)
    {
    }
}
