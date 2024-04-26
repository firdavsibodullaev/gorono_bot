<?php

namespace App\Modules\Telegram\DTOs\Response;

class ErrorResponseDTO
{
    public function __construct(
        public bool    $ok,
        public int     $error_code,
        public ?string $description = null,
    )
    {
    }

    public static function fromArray(array $response): ErrorResponseDTO
    {
        return new self(
            ok: $response['ok'],
            error_code: $response['error_code'],
            description: $response['description'] ?? null
        );
    }
}
