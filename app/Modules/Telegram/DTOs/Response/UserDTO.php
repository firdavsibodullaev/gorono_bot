<?php

namespace App\Modules\Telegram\DTOs\Response;

class UserDTO
{
    public function __construct(
        public int     $id,
        public bool    $is_bot,
        public string  $first_name,
        public ?string $username = null
    )
    {
    }

    public static function fromArray(array $user): static
    {
        return new static(
            id: $user['id'],
            is_bot: $user['is_bot'],
            first_name: $user['first_name'],
            username: $user['username'] ?? null
        );
    }
}
