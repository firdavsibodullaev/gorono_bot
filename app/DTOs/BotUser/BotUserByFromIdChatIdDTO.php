<?php

namespace App\DTOs\BotUser;

use App\DTOs\BaseDTO;

class BotUserByFromIdChatIdDTO extends BaseDTO
{
    public function __construct(
        public int $from_id,
        public int $chat_id
    )
    {
    }
}
