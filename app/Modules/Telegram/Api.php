<?php

namespace App\Modules\Telegram;

use App\Modules\Telegram\DTOs\Request\BaseDTO;
use App\Modules\Telegram\DTOs\Request\BaseFileDTO;
use App\Modules\Telegram\Enums\Method;
use App\Modules\Telegram\Exceptions\BadRequestException;
use App\Modules\Telegram\Exceptions\BaseException;
use App\Modules\Telegram\Exceptions\TelegramTokenNotExistException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Http;

class Api
{
    protected string $token;

    protected string $base_url_pattern = "https://api.telegram.org/bot{token}";
    protected string $base_url = "https://api.telegram.org/bot{token}";

    /**
     * @throws TelegramTokenNotExistException
     */
    public function __construct()
    {
        $config = config('services.telegram');

        if (!$config['token']) {
            throw new TelegramTokenNotExistException("Telegram bot token does not exist");
        }

        $this->token = $config['token'];
        $this->makeBaseUrl();
    }

    public static function make(): static
    {
        return new static();
    }

    private function makeBaseUrl(): void
    {
        $this->base_url = str($this->base_url_pattern)->replace('{token}', $this->token);
    }

    /**
     * @throws ConnectionException
     * @throws BaseException
     */
    public function send(Method $method, ?BaseDTO $dto = null): array
    {
        $request = $this->getClient()->get($method->value, $dto?->toArray() ?: []);

        Context::add([
            'telegram-request-payload' => [
                'method' => $method->value,
                'params' => $dto?->toArray()
            ],
            'telegram-response' => $request->json() ?? $request->body()
        ]);

        $response = $request->json();

        if ($response['ok'] === false) {
            throw BaseException::throw($response);
        }

        return $response;
    }

    /**
     * @throws ConnectionException
     * @throws BadRequestException
     * @throws BaseException
     */
    public function sendFile(Method $method, BaseFileDTO $dto): array
    {
        $payload = $dto->toArray();

        if ($dto->file instanceof UploadedFile) {
            $file_type = $method->fileType();
            unset($payload[$file_type]);

            $request = $this->getClient()
                ->attach($file_type, $dto->file->getContent(), $dto->file->getClientOriginalName())
                ->post($method->value, $payload);
        } else {
            $request = $this->getClient()
                ->get($method->value, $payload);
        }

        Context::add([
            'telegram-request-payload' => [
                'method' => $method->value,
                'params' => $dto->toArray()
            ],
            'telegram-response' => $request->json() ?? $request->body()
        ]);

        $response = $request->json();

        if ($response['ok'] === false) {
            throw BaseException::throw($response);
        }

        return $response;
    }

    protected function getClient(): PendingRequest
    {
        return Http::baseUrl($this->base_url);
    }
}
