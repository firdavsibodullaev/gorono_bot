<?php

namespace App\Modules\Telegram\DTOs\Request;

class SendMessageDTO extends BaseDTO
{
    public function __construct(
        public int    $chat_id,
        public string $text,
        public string $parse_mode = 'html',
        public ?string  $reply_markup = null,
        public array  $reply_parameters = [],
    )
    {
        $this->container = get_defined_vars();
    }
}
