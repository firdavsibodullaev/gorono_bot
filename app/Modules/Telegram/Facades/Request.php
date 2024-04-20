<?php

namespace App\Modules\Telegram\Facades;

use App\Modules\Telegram\DTOs\Request\GetUpdatesDTO as GetUpdatesResponseDTO;
use App\Modules\Telegram\DTOs\Response\SendMessageDTO;
use Illuminate\Support\Facades\Facade;

/**
 * @method static GetUpdatesResponseDTO getUpdates(GetUpdatesResponseDTO $payload = new GetUpdatesResponseDTO())
 * @method static SendMessageDTO sendMessage(int $chat_id, string $text, string $parse_mode = 'html', ?string $reply_markup = null, array $reply_parameters = [])
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
