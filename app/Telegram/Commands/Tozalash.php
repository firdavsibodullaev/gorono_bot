<?php

namespace App\Telegram\Commands;

use App\Actions\BotUser\BotUserByFromIdChatIdAction;
use App\Models\BotUser;
use App\Modules\Telegram\DTOs\Response\MessageDTO;
use Illuminate\Support\Facades\Artisan;

class Tozalash
{
    public int $from_id;
    public int $chat_id;
    protected ?BotUser $user;

    public function __construct(protected MessageDTO $message)
    {
        $this->from_id = $this->message->from->id;
        $this->chat_id = $this->message->chat->id;
        $this->user = BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run();
    }

    public function __invoke(): void
    {
        $this->user->surveys()->forceDelete();
        $this->user->forceDelete();
        Artisan::call('cache:clear');
    }
}
