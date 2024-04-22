<?php

namespace App\Modules\Telegram\DTOs\Response;

use App\Modules\Telegram\Enums\ChatType;

class ChatDTO
{
    public function __construct(
        public int      $id,
        public ChatType $type,
        public ?string  $first_name = null,
        public ?string  $title = null,
        public ?string  $username = null,
    )
    {
    }

    public static function fromArray(array $chat): static
    {
        return new static(
            id: $chat['id'],
            type: ChatType::tryFrom($chat['type']),
            first_name: $chat['first_name'] ?? null,
            title: $chat['title'] ?? null,
            username: $chat['username'] ?? null,
        );
    }
}
