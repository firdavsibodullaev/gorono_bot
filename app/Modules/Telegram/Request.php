<?php

namespace App\Modules\Telegram;

use App\Modules\Telegram\DTOs\Request\GetUpdatesDTO as GetUpdatesRequestDTO;
use App\Modules\Telegram\DTOs\Request\SendMessageDTO;
use App\Modules\Telegram\DTOs\Response\GetUpdatesDTO;
use App\Modules\Telegram\DTOs\Response\SendMessageDTO as SendMessageResponseDTO;
use App\Modules\Telegram\Enums\Method;
use Illuminate\Http\Client\ConnectionException;

class Request
{
    public function __construct(protected Api $api)
    {
    }

    /**
     * @throws ConnectionException
     */
    public function getUpdates(GetUpdatesRequestDTO $payload = new GetUpdatesRequestDTO()): GetUpdatesDTO
    {
        $response = $this->api->send(Method::getUpdates, $payload);
        return GetUpdatesDTO::fromArray($response);
    }

    public function sendMessage(
        int     $chat_id,
        string  $text,
        string  $parse_mode = 'html',
        ?string $reply_markup = null,
        array   $reply_parameters = [],
    ): SendMessageResponseDTO
    {
        $payload = new SendMessageDTO(
            chat_id: $chat_id,
            text: $text,
            parse_mode: $parse_mode,
            reply_markup: $reply_markup,
            reply_parameters: $reply_parameters
        );

        $response = $this->api->send(Method::sendMessage, $payload);

        return SendMessageResponseDTO::fromArray($response);
    }
}
