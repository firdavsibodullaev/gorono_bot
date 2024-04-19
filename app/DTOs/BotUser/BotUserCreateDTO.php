<?php

namespace App\DTOs\BotUser;

use App\DTOs\BaseDTO;
use App\Modules\Telegram\Enums\ChatMemberStatus;

class BotUserCreateDTO extends BaseDTO
{
    public function __construct(
        public int $from_id,
        public int $chat_id,
        public ChatMemberStatus $status,
    )
    {
    }
}
