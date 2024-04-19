<?php

namespace App\Modules\Telegram\DTOs\Response;

class FromDTO
{
    public function __construct(
        public int     $id,
        public bool    $is_bot,
        public string  $first_name,
        public ?string $username = null,
        public ?string $language_code = null,
    )
    {
    }

    public static function fromArray(array $from): static
    {
        return new static(
            id: $from['id'],
            is_bot: $from['is_bot'],
            first_name: $from['first_name'],
            username: $from['username'],
            language_code: $from['language_code'],
        );
    }
}
