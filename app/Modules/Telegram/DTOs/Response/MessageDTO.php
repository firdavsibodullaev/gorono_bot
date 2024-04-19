<?php

namespace App\Modules\Telegram\DTOs\Response;

class MessageDTO
{
    public function __construct(
        public int     $message_id,
        public FromDTO $from,
        public ChatDTO $chat,
        public int     $date,
        public ?string $text = null,
        public ?string $caption = null
    )
    {
    }

    public static function fromArray(array $message): static
    {
        return new static(
            message_id: $message['message_id'],
            from: FromDTO::fromArray($message['from']),
            chat: ChatDTO::fromArray($message['chat']),
            date: $message['date'],
            text: $message['text'] ?? null,
            caption: $message['caption'] ?? null,
        );
    }
}
