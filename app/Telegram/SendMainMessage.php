<?php

namespace App\Telegram;

use App\Actions\BotUser\BotUserByFromIdChatIdAction;
use App\Exceptions\WrongInstanceException;
use App\Models\BotUser;
use App\Modules\Telegram\Facades\Request;

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

    public static function send(int $from_id, int $chat_id)
    {
        return (new static($from_id, $chat_id))();
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

        Request::sendMessage(
            $this->chat_id,
            __('Maktabni bitirganingizdan so\'ng nima qilmoqchisiz?'),
            reply_markup: json_encode([
                'keyboard' => Keyboard::afterSchoolGoal(),
                'resize_keyboard' => true,
            ])
        );
    }
}
