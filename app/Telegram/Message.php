<?php

namespace App\Telegram;

use App\Exceptions\UpdateNotPermittedException;
use App\Modules\Telegram\DTOs\Response\MessageDTO;
use App\Modules\Telegram\DTOs\Response\UpdateDTO;
use App\Modules\Telegram\Enums\ChatType;

class Message extends BaseUpdate
{
    protected MessageDTO $message;
    protected int $chat_id;
    protected ?string $text;

    public function __construct(protected UpdateDTO $update)
    {
        parent::__construct($this->update);

        if (!$update->message || !$update->message->chat->type->is(ChatType::Private)) {
            throw new UpdateNotPermittedException("Only private chat is allowed");
        }

        $this->message = $update->message;
        $this->chat_id = $update->message->chat->id;
        $this->text = $update->message->text ?: $update->message->caption;

    }

    public function index()
    {
        dump($this->text);
    }
}
