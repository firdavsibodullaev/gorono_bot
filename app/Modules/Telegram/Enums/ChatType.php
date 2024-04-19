<?php

namespace App\Modules\Telegram\Enums;

enum ChatType: string
{
    case Private = 'private';
    case Group = 'group';
    case Supergroup = 'supergroup';
    case Channel = 'channel';

    public function is(ChatType $type)
    {
        return $this === $type;
    }
}
