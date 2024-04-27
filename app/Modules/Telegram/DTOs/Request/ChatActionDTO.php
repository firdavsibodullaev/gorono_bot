<?php

namespace App\Modules\Telegram\DTOs\Request;

use App\Modules\Telegram\Enums\ChatAction;

class ChatActionDTO extends BaseDTO
{
    public function __construct(
        public int        $chat_id,
        public ChatAction $action
    )
    {
        $this->container = get_defined_vars();
    }
}
