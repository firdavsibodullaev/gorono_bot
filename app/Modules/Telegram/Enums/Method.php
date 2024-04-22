<?php

namespace App\Modules\Telegram\Enums;

enum Method: string
{
    case GetUpdates = 'getUpdates';
    case SendMessage = 'sendMessage';
    case EditMessageText = 'editMessageText';
    case SetWebhook = 'setWebhook';
}
