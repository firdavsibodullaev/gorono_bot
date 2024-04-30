<?php

namespace App\Modules\Telegram\Enums;

use App\Modules\Telegram\Traits\InteractsWithFileTypes;

enum Method: string
{
    use InteractsWithFileTypes;

    case GetUpdates = 'getUpdates';
    case SendMessage = 'sendMessage';
    case EditMessageText = 'editMessageText';
    case SetWebhook = 'setWebhook';
    case SendDocument = 'sendDocument';
    case SendChatAction = 'sendChatAction';
    case SendPhoto = 'sendPhoto';
}
