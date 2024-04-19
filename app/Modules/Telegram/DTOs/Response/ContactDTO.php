<?php

namespace App\Modules\Telegram\DTOs\Response;

class ContactDTO
{
    public function __construct(
        public string $phone_number,
        public string $first_name,
        public int    $user_id
    )
    {
    }

    public static function fromArray(array $contact): static
    {
        return new static(
            phone_number: $contact['phone_number'],
            first_name: $contact['first_name'],
            user_id: $contact['user_id']
        );
    }
}
