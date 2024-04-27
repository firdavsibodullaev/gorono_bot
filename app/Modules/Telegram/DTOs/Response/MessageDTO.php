<?php

namespace App\Modules\Telegram\DTOs\Response;

use App\Modules\Telegram\Facades\Request;

class MessageDTO
{
    public function __construct(
        public int          $message_id,
        public FromDTO      $from,
        public ChatDTO      $chat,
        public int          $date,
        public ?MessageDTO  $reply_to_message = null,
        public ?ContactDTO  $contact = null,
        public ?string      $text = null,
        public ?string      $caption = null,
        public ?DocumentDTO $document = null,
    )
    {
    }

    public static function fromArray(array $message): static
    {
        $has_contact = isset($message['contact']);
        $has_reply_to_message = isset($message['reply_to_message']);

        $has_document = isset($message['document']);

        return new static(
            message_id: $message['message_id'],
            from: FromDTO::fromArray($message['from']),
            chat: ChatDTO::fromArray($message['chat']),
            date: $message['date'],
            reply_to_message: $has_reply_to_message ? MessageDTO::fromArray($message['reply_to_message']) : null,
            contact: $has_contact ? ContactDTO::fromArray($message['contact']) : null,
            text: $message['text'] ?? null,
            caption: $message['caption'] ?? null,
            document: $has_document ? DocumentDTO::fromArray($message['document']) : null
        );
    }

    public function isCommand(): bool
    {
        $possible_command = str($this->text)->lower();

        return $possible_command->startsWith('/') && $possible_command
                ->replaceMatches('/^\//', '')
                ->isMatch('/^[a-z]+$/');
    }

    public function sendMessage(string $text, string $parse_mode = 'html', ?string $reply_markup = null, array $reply_parameters = []): SendMessageDTO
    {
        return Request::sendMessage($this->chat->id, $text, $parse_mode, $reply_markup, $reply_parameters);
    }
}
