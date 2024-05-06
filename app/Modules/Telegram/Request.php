<?php

namespace App\Modules\Telegram;

use App\Modules\Telegram\DTOs\Request\ChatActionDTO;
use App\Modules\Telegram\DTOs\Request\EditMessageDTO;
use App\Modules\Telegram\DTOs\Request\GetUpdatesDTO;
use App\Modules\Telegram\DTOs\Request\SendDocumentDTO;
use App\Modules\Telegram\DTOs\Request\SendMessageDTO;
use App\Modules\Telegram\DTOs\Request\SendPhotoDTO;
use App\Modules\Telegram\DTOs\Request\SetWebhookDTO;
use App\Modules\Telegram\DTOs\Response\ErrorResponseDTO;
use App\Modules\Telegram\DTOs\Response\GetUpdatesDTO as GetUpdatesResponseDTO;
use App\Modules\Telegram\DTOs\Response\SendMessageDTO as SendMessageResponseDTO;
use App\Modules\Telegram\DTOs\Response\SuccessEmptyDTO;
use App\Modules\Telegram\DTOs\Response\UpdateDTO;
use App\Modules\Telegram\DTOs\Response\WebhookDTO;
use App\Modules\Telegram\Enums\ChatAction;
use App\Modules\Telegram\Enums\Method;
use App\Modules\Telegram\Exceptions\BadRequestException;
use App\Modules\Telegram\Exceptions\BaseException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request as FacadeRequest;
use Illuminate\Http\UploadedFile;

class Request
{
    public function __construct(protected Api $api)
    {
    }

    /**
     * @param int|null $offset
     * @param int|null $limit
     * @param int|null $timeout
     * @param array|null $allowed_updates
     * @return GetUpdatesResponseDTO
     * @throws ConnectionException
     * @throws BaseException
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

    public function getWebhookUpdates(FacadeRequest $request): UpdateDTO
    {
        return UpdateDTO::fromArray($request->all());
    }

    /**
     * @param string $url
     * @param UploadedFile|null $certificate
     * @param string|null $ip_address
     * @param int $max_connections
     * @param array|null $allowed_updates
     * @param bool $drop_pending_updates
     * @param string|null $secret_token
     * @return WebhookDTO|ErrorResponseDTO
     * @throws BaseException
     * @throws ConnectionException
     */
    public function setWebhook(
        string        $url,
        ?UploadedFile $certificate = null,
        ?string       $ip_address = null,
        int           $max_connections = 40,
        ?array        $allowed_updates = null,
        bool          $drop_pending_updates = false,
        ?string       $secret_token = null
    ): WebhookDTO|ErrorResponseDTO
    {
        $payload = new SetWebhookDTO($url, $certificate, $ip_address, $max_connections, $allowed_updates, $drop_pending_updates, $secret_token);

        $response = $this->api->send(Method::SetWebhook, $payload);

        return $response['ok']
            ? WebhookDTO::fromArray($response)
            : ERrorResponseDTO::fromArray($response);
    }

    /**
     * @param int $chat_id
     * @param string $text
     * @param string $parse_mode
     * @param string|null $reply_markup
     * @param array $reply_parameters
     * @return SendMessageResponseDTO
     * @throws BaseException
     * @throws ConnectionException
     */
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
     * @param int $chat_id
     * @param UploadedFile|string $photo
     * @param string|null $caption
     * @param string|null $parse_mode
     * @param array|null $caption_entities
     * @param string|null $reply_markup
     * @param array $reply_parameters
     * @return SendMessageResponseDTO
     * @throws BadRequestException
     * @throws BaseException
     * @throws ConnectionException
     */
    public function sendPhoto(
        int                 $chat_id,
        UploadedFile|string $photo,
        string|null         $caption,
        ?string             $parse_mode = 'html',
        ?array              $caption_entities = null,
        ?string             $reply_markup = null,
        array               $reply_parameters = []
    ): SendMessageResponseDTO
    {
        $payload = new SendPhotoDTO(
            chat_id: $chat_id,
            photo: $photo,
            caption: $caption,
            parse_mode: $parse_mode,
            caption_entities: $caption_entities ? json_encode($caption_entities) : null,
            reply_markup: $reply_markup,
            reply_parameters: $reply_parameters
        );

        $response = $this->api->sendFile(Method::SendPhoto, $payload);

        return SendMessageResponseDTO::fromArray($response);
    }

    /**
     * @param int $chat_id
     * @param UploadedFile|string $document
     * @param UploadedFile|string|null $thumbnail
     * @param string|null $caption
     * @param string $parse_mode
     * @param string|null $reply_markup
     * @param array $reply_parameters
     * @return SendMessageResponseDTO
     * @throws BadRequestException
     * @throws BaseException
     * @throws ConnectionException
     */
    public function sendDocument(
        int                      $chat_id,
        UploadedFile|string      $document,
        UploadedFile|string|null $thumbnail = null,
        ?string                  $caption = null,
        string                   $parse_mode = 'html',
        ?string                  $reply_markup = null,
        array                    $reply_parameters = [],
    ): SendMessageResponseDTO
    {
        $payload = new SendDocumentDTO(
            chat_id: $chat_id,
            document: $document,
            thumbnail: $thumbnail,
            caption: $caption,
            parse_mode: $parse_mode,
            reply_markup: $reply_markup,
            reply_parameters: $reply_parameters
        );

        $response = $this->api->sendFile(Method::SendDocument, $payload);

        return SendMessageResponseDTO::fromArray($response);
    }

    /**
     * @param int $chat_id
     * @param ChatAction $action
     * @return SuccessEmptyDTO
     * @throws BaseException
     * @throws ConnectionException
     */
    public function sendChatAction(int $chat_id, ChatAction $action): SuccessEmptyDTO
    {
        $payload = new ChatActionDTO($chat_id, $action);

        $response = $this->api->send(Method::SendChatAction, $payload);

        return new SuccessEmptyDTO(...$response);
    }

    /**
     * @param int $chat_id
     * @param int $message_id
     * @param string $text
     * @param string $parse_mode
     * @param string|null $reply_markup
     * @return SendMessageResponseDTO|ErrorResponseDTO
     * @throws BaseException
     * @throws ConnectionException
     */
    public function editMessageText(
        int     $chat_id,
        int     $message_id,
        string  $text,
        string  $parse_mode = 'html',
        ?string $reply_markup = null
    ): SendMessageResponseDTO|ErrorResponseDTO
    {
        $payload = new EditMessageDTO(
            chat_id: $chat_id,
            message_id: $message_id,
            text: $text,
            parse_mode: $parse_mode,
            reply_markup: $reply_markup
        );

        $response = $this->api->send(Method::EditMessageText, $payload);

        return SendMessageResponseDTO::fromArray($response);
    }
}
