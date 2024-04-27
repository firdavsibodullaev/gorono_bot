<?php

namespace App\Telegram\Commands;

use App\Events\HandleExports;
use App\Modules\Telegram\DTOs\Response\MessageDTO;
use App\Modules\Telegram\Enums\ChatAction;
use App\Modules\Telegram\Facades\Request;

class Export
{
    public int $from_id;
    public int $chat_id;

    public function __construct(protected MessageDTO $message)
    {
        $this->from_id = $this->message->from->id;
        $this->chat_id = $this->message->chat->id;
    }

    public function __invoke(): void
    {
        Request::sendChatAction($this->chat_id, ChatAction::UploadDocument);
        HandleExports::dispatch($this->from_id, $this->chat_id);
    }
}
