<?php

namespace App\Modules\Telegram\DTOs\Response;

class EditMessageDTO
{
    public function __construct(
        public int     $chat_id,
        public int     $message_id,
        public string  $text,
        public string  $parse_mode = 'html',
        public ?string $reply_markup = null
    )
    {
    }

    public static function fromArray(array $response)
    {
    }
}
