<?php

namespace App\Modules\Telegram\DTOs\Request;

class EditMessageDTO extends BaseDTO
{
    public function __construct(
        public int     $chat_id,
        public int     $message_id,
        public string  $text,
        public string  $parse_mode = 'html',
        public ?string $reply_markup = null
    )
    {
        $this->container = get_defined_vars();
    }
}
