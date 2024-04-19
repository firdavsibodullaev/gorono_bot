<?php

namespace App\Modules\Telegram\DTOs\Response;

class UpdateDTO
{
    public function __construct(
        public int              $update_id,
        public ?MessageDTO      $message = null,
        public ?MyChatMemberDTO $my_chat_member = null,
    )
    {
    }

    public static function fromArray(array $update): static
    {
        $has_message = isset($update['message']);
        $has_my_chat_member = isset($update['my_chat_member']);
        return new static(
            update_id: $update['update_id'],
            message: $has_message ? MessageDTO::fromArray($update['message']) : null,
            my_chat_member: $has_my_chat_member ? MyChatMemberDTO::fromArray($update['my_chat_member']) : null,
        );
    }
}
