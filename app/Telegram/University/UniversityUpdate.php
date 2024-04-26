<?php

namespace App\Telegram\University;

use App\Actions\BotUser\BotUserByFromIdChatIdAction;
use App\Models\BotUser;
use App\Modules\Telegram\Facades\Request;
use App\Telegram\Action\Action;
use App\Telegram\Keyboard;

class UniversityUpdate
{
    protected BotUser $user;

    public function __construct(protected int $from_id, protected int $chat_id)
    {
        Action::make($this->from_id, $this->chat_id)->clear();

        $this->user = BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run();
    }

    public function index(): void
    {
        Request::sendMessage(
            $this->chat_id,
            __('Barcha yangiliklarni shu erda kuzatib boring'),
            reply_markup: Keyboard::remove()
        );
    }
}
