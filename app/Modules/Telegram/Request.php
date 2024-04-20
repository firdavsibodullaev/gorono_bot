<?php

namespace App\Modules\Telegram;

use App\Modules\Telegram\DTOs\Request\EditMessageDTO;
use App\Modules\Telegram\DTOs\Request\GetUpdatesDTO;
use App\Modules\Telegram\DTOs\Request\SendMessageDTO;
use App\Modules\Telegram\DTOs\Response\EditMessageDTO as EditMessageResponseDTO;
use App\Modules\Telegram\DTOs\Response\GetUpdatesDTO as GetUpdatesResponseDTO;
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
    public function getUpdates(
        ?int   $offset = null,
        ?int   $limit = null,
        ?int   $timeout = null,
        ?array $allowed_updates = null
    ): GetUpdatesResponseDTO
    {
        $payload = new GetUpdatesDTO($offset, $limit, $timeout, $allowed_updates,);

        $response = $this->api->send(Method::GetUpdates, $payload);

        return GetUpdatesResponseDTO::fromArray($response);
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

        $response = $this->api->send(Method::SendMessage, $payload);

        return SendMessageResponseDTO::fromArray($response);
    }

    /**
     * @throws ConnectionException
     */
    public function editMessageText(
        int     $chat_id,
        int     $message_id,
        string  $text,
        string  $parse_mode = 'html',
        ?string $reply_markup = null
    ): EditMessageResponseDTO
    {
        $payload = new EditMessageDTO(
            chat_id: $chat_id,
            message_id: $message_id,
            text: $text,
            parse_mode: $parse_mode,
            reply_markup: $reply_markup
        );

        $response = $this->api->send(Method::EditMessageText, $payload);

        return EditMessageResponseDTO::fromArray($response);
    }
}
