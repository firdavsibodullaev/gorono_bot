<?php

namespace App\Modules\Telegram\DTOs\Response;

use App\Modules\Telegram\Enums\ChatType;

class ChatDTO
{
    public function __construct(
        public int      $id,
        public string   $first_name,
        public ChatType $type,
        public ?string  $username = null,
    )
    {
    }

    public static function fromArray(array $chat): static
    {
        return new static(
            id: $chat['id'],
            first_name: $chat['first_name'],
            type: ChatType::tryFrom($chat['type']),
            username: $chat['username'],
        );
    }
}
