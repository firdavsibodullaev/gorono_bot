<?php

namespace App\Telegram\Update\Message\Private;

use App\Actions\BotUser\BotUserByFromIdChatIdAction;
use App\Enums\BotUserType;
use App\Models\BotUser;
use App\Telegram\Action\Action;
use App\Telegram\School\SchoolUpdate;
use App\Telegram\University\UniversityUpdate;

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
        match ($this->user->type) {
            BotUserType::School => (new SchoolUpdate($this->from_id, $this->chat_id))->index(),
            BotUserType::Student => (new UniversityUpdate($this->from_id, $this->chat_id))->index()
        };
    }
}
