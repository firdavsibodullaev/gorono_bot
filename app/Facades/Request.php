<?php

namespace App\Facades;

use App\Modules\Telegram\DTOs\Request\GetUpdatesDTO;
use App\Modules\Telegram\DTOs\Request\GetUpdatesDTO as GetUpdatesResponseDTO;
use App\Modules\Telegram\DTOs\Response\SendMessageDTO;
use Illuminate\Support\Facades\Facade;

/**
 * @method static GetUpdatesResponseDTO getUpdates(GetUpdatesDTO $payload = new GetUpdatesDTO())
 * @method static SendMessageDTO sendMessage(int $chat_id, string $text, string $parse_mode = 'html', string $reply_markup = [], array $reply_parameters = [])
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
