<?php

namespace App\Modules\Telegram\Facades;

use App\Modules\Telegram\DTOs\Response\EditMessageDTO;
use App\Modules\Telegram\DTOs\Response\ErrorResponseDTO;
use App\Modules\Telegram\DTOs\Response\GetUpdatesDTO;
use App\Modules\Telegram\DTOs\Response\SendMessageDTO;
use App\Modules\Telegram\DTOs\Response\SuccessEmptyDTO;
use App\Modules\Telegram\DTOs\Response\UpdateDTO;
use App\Modules\Telegram\DTOs\Response\WebhookDTO;
use App\Modules\Telegram\Enums\ChatAction;
use Illuminate\Http\Request as FacadeRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Facade;

/**
 * @method static GetUpdatesDTO getUpdates(?int $offset = null, ?int $limit = null, ?int $timeout = null, ?array $allowed_updates = null)
 * @method static SendMessageDTO sendMessage(int $chat_id, string $text, string $parse_mode = 'html', ?string $reply_markup = null, array $reply_parameters = [])
 * @method static SendMessageDTO sendDocument(int $chat_id, UploadedFile|string $document, UploadedFile|string|null $thumbnail = null, ?string $caption = null, string $parse_mode = 'html', ?string $reply_markup = null, array $reply_parameters = [])
 * @method static EditMessageDTO editMessageText(int $chat_id, int $message_id, string $text, string $parse_mode = 'html', ?string $reply_markup = null)
 * @method static UpdateDTO getWebhookUpdates(FacadeRequest $request)
 * @method static WebhookDTO|ErrorResponseDTO setWebhook(string $url, ?UploadedFile $certificate = null, ?string $ip_address = null, int $max_connections = 40, ?array $allowed_updates = null, bool $drop_pending_updates = false, ?string $secret_token = null)
 * @method static SuccessEmptyDTO sendChatAction(int $chat_id, ChatAction $UploadDocument)
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
