<?php

namespace App\Telegram;

use App\Actions\BotUser\BotUserByFromIdChatIdAction;
use App\Enums\Method;
use App\Exceptions\WrongInstanceException;
use App\Facades\Request;
use App\Models\BotUser;
use App\Telegram\Action\Action;

class SendMainMessage
{
    protected BotUser $user;

    protected Request $api;

    public function __construct(protected int $from_id, protected int $chat_id)
    {
        try {
            $this->user = BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run();
        } catch (WrongInstanceException) {
        }
    }

    public function __invoke(): void
    {
        $user = BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run();

        if ($user->has_survey) {
            Request::sendMessage(
                $this->chat_id,
                'Siz so\'rovnomadan o\'tib bo\'lgansiz',
                reply_markup: json_encode([
                    'remove_keyboard' => true
                ])
            );
            return;
        }

        Request::sendMessage($this->chat_id, 'test', reply_markup: json_encode([
            'remove_keyboard' => true
        ]));
    }
}
