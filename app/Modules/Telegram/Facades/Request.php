<?php

namespace App\Modules\Telegram\Facades;

use App\Modules\Telegram\DTOs\Response\EditMessageDTO;
use App\Modules\Telegram\DTOs\Response\GetUpdatesDTO;
use App\Modules\Telegram\DTOs\Response\SendMessageDTO;
use Illuminate\Support\Facades\Facade;

/**
 * @method static GetUpdatesDTO getUpdates(?int $offset = null, ?int $limit = null, ?int $timeout = null, ?array $allowed_updates = null)
 * @method static SendMessageDTO sendMessage(int $chat_id, string $text, string $parse_mode = 'html', ?string $reply_markup = null, array $reply_parameters = [])
 * @method static EditMessageDTO editMessageText(int $chat_id, int $message_id, string $text, string $parse_mode = 'html', ?string $reply_markup = null)
 *
 * @see \App\Modules\Telegram\Request
 */
class Request extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'telegram.request';
    }
}
