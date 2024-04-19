<?php

namespace App\Modules\Telegram\Enums;

enum AllowedUpdates: string
{
    case Message = 'message';
    case MyChatMember = 'my_chat_member';
}
