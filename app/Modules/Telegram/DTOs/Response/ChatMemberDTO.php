<?php

namespace App\Modules\Telegram\DTOs\Response;

use App\Modules\Telegram\Enums\ChatMemberStatus;

class ChatMemberDTO
{
    public function __construct(
        public UserDTO          $user,
        public ChatMemberStatus $status,
        public ?int             $until_date = null
    )
    {
    }

    public static function fromArray(array $old_chat_member)
    {
        return new static(
            user: UserDTO::fromArray($old_chat_member['user']),
            status: ChatMemberStatus::tryFrom($old_chat_member['status']),
            until_date: $old_chat_member['until_date'] ?? null,
        );
    }
}
