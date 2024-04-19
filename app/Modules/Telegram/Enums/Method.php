<?php

namespace App\Modules\Telegram\Enums;

enum Method: string
{
    case getUpdates = 'getUpdates';
    case sendMessage = 'sendMessage';
}
