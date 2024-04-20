<?php

namespace App\DTOs\BotUser;

use App\DTOs\BaseDTO;
use App\Enums\Language;
use App\Modules\Telegram\DTOs\Response\MessageDTO;
use App\Modules\Telegram\Enums\ChatMemberStatus;

class BotUserCreateDTO extends BaseDTO
{
    public function __construct(
        public int              $from_id,
        public int              $chat_id,
        public ChatMemberStatus $status,
        public Language         $language = Language::Uz
    )
    {
    }

    public static function fromMessage(MessageDTO $message, ChatMemberStatus $member = ChatMemberStatus::Member): static
    {
        return new static(
            $message->from->id,
            $message->chat->id,
            $member,
            Language::tryFrom($message->from->language_code) ?? Language::Uz
        );
    }
}
