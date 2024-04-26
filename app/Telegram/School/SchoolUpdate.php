<?php

namespace App\Telegram\School;

use App\Actions\BotUser\BotUserByFromIdChatIdAction;
use App\Models\BotUser;
use App\Modules\Telegram\Facades\Request;
use App\Telegram\Action\Action;
use App\Telegram\Keyboard;

class SchoolUpdate
{
    protected BotUser $user;

    public function __construct(protected int $from_id, protected int $chat_id)
    {
        Action::make($this->from_id, $this->chat_id)->clear();

        $this->user = BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run();
    }

    public function index(): void
    {
        if ($this->user->has_survey) {
            Request::sendMessage($this->chat_id, __('Barcha yangiliklarni shu erda kuzatib boring'));
            return;
        }

        Request::sendMessage(
            $this->chat_id,
            __('Maktabni bitirganingizdan so\'ng nima qilmoqchisiz?'),
            reply_markup: Keyboard::afterSchoolGoal()
        );
    }
}
