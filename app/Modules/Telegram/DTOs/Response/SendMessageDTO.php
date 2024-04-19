<?php

namespace App\Modules\Telegram\DTOs\Response;

class SendMessageDTO
{
    public function __construct(
        public bool       $ok,
        public MessageDTO $result
    )
    {
    }

    public static function fromArray(array $message): static
    {
        return new static(
            ok: $message['ok'],
            result: MessageDTO::fromArray($message['result'])
        );
    }
}
