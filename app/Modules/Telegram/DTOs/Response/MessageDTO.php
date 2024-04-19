<?php

namespace App\Modules\Telegram\DTOs\Response;

class MessageDTO
{
    public function __construct(
        public int         $message_id,
        public FromDTO     $from,
        public ChatDTO     $chat,
        public int         $date,
        public ?MessageDTO $reply_to_message = null,
        public ?ContactDTO $contact = null,
        public ?string     $text = null,
        public ?string     $caption = null
    )
    {
    }

    public static function fromArray(array $message): static
    {
        $has_contact = isset($message['contact']);
        $has_reply_to_message = isset($message['reply_to_message']);
        return new static(
            message_id: $message['message_id'],
            from: FromDTO::fromArray($message['from']),
            chat: ChatDTO::fromArray($message['chat']),
            date: $message['date'],
            reply_to_message: $has_reply_to_message ? MessageDTO::fromArray($message['reply_to_message']) : null,
            contact: $has_contact ? ContactDTO::fromArray($message['contact']) : null,
            text: $message['text'] ?? null,
            caption: $message['caption'] ?? null,
        );
    }

    public function isCommand(): bool
    {
        $possible_command = str($this->text)->lower();

        return $possible_command->startsWith('/') && $possible_command
                ->replaceMatches('/^\//', '')
                ->isMatch('/^[a-z]+$/');
    }
}
