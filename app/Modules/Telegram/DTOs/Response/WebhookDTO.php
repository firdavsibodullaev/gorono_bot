<?php

namespace App\Modules\Telegram\DTOs\Response;

class WebhookDTO
{
    public function __construct(
        public bool   $ok,
        public bool   $result,
        public string $description,
    )
    {
    }

    public static function fromArray(array $response): WebhookDTO
    {
        return new self(
            ok: $response['ok'],
            result: $response['result'],
            description: $response['description']
        );
    }
}
