<?php

namespace App\Modules\Telegram\DTOs\Response;

use App\Modules\Telegram\Facades\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class MessageDTO
{
    public function __construct(
        public int          $message_id,
        public FromDTO      $from,
        public ChatDTO      $chat,
        public int          $date,
        public ?int         $edit_date = null,
        public ?MessageDTO  $reply_to_message = null,
        public ?ContactDTO  $contact = null,
        public ?string      $text = null,
        public ?string      $caption = null,
        public ?array       $caption_entities = null,
        public ?DocumentDTO $document = null,
        public ?Collection  $photo = null
    )
    {
        $this->photo?->ensure(PhotoDTO::class);
    }

    public static function fromArray(array $message): static
    {
        $has_contact = isset($message['contact']);
        $has_reply_to_message = isset($message['reply_to_message']);

        $has_document = isset($message['document']);
        $has_photo = isset($message['photo']);

        return new static(
            message_id: $message['message_id'],
            from: FromDTO::fromArray($message['from']),
            chat: ChatDTO::fromArray($message['chat']),
            date: $message['date'],
            edit_date: $message['edit_date'] ?? null,
            reply_to_message: $has_reply_to_message ? MessageDTO::fromArray($message['reply_to_message']) : null,
            contact: $has_contact ? ContactDTO::fromArray($message['contact']) : null,
            text: $message['text'] ?? null,
            caption: $message['caption'] ?? null,
            caption_entities: $message['caption_entities'] ?? null,
            document: $has_document ? DocumentDTO::fromArray($message['document']) : null,
            photo: $has_photo ? PhotoDTO::fromArray($message['photo']) : null
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

    public function sendPhoto(UploadedFile|string $photo, ?string $caption = null, $parse_mode = 'html', ?array $caption_entities = null, ?string $reply_markup = null, array $reply_parameters = []): SendMessageDTO
    {
        return Request::sendPhoto($this->chat->id, $photo, $caption, $parse_mode, $caption_entities, $reply_markup, $reply_parameters);
    }
}
