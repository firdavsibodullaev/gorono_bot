<?php

namespace App\Modules\Telegram\DTOs\Response;

class MyChatMemberDTO
{
    public function __construct(
        public ChatDTO       $chat,
        public FromDTO       $from,
        public int           $date,
        public ChatMemberDTO $old_chat_member,
        public ChatMemberDTO $new_chat_member,
    )
    {
    }

    public static function fromArray(array $my_chat_member)
    {
        return new static(
            chat: ChatDTO::fromArray($my_chat_member['chat']),
            from: FromDTO::fromArray($my_chat_member['from']),
            date: $my_chat_member['date'],
            old_chat_member: ChatMemberDTO::fromArray($my_chat_member['old_chat_member']),
            new_chat_member: ChatMemberDTO::fromArray($my_chat_member['new_chat_member']),
        );
    }
}
