<?php

namespace App\Telegram\Update\Message\Private;

use App\Actions\BotUser\BotUserByFromIdChatIdAction;
use App\Models\BotUser;
use App\Modules\Telegram\Facades\Request;
use App\Telegram\Action\Action;
use App\Telegram\Keyboard;

class SendMainMessage
{
    protected BotUser $user;

    public function __construct(protected int $from_id, protected int $chat_id)
    {
        Action::make($this->from_id, $this->chat_id)->clear();

        $this->user = BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run();
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
                reply_markup: Keyboard::remove()
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
