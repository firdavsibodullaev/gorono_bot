<?php

namespace App\Modules\Telegram;

use App\Modules\Telegram\DTOs\Request\BaseDTO;
use App\Modules\Telegram\DTOs\Response\GetUpdatesDTO;
use App\Modules\Telegram\Enums\Method;

class Request
{
    public function __construct(protected Api $api)
    {
    }

    public function getUpdates(?BaseDTO $payload = null): GetUpdatesDTO
    {
        $response = $this->api->send(Method::getUpdates, $payload);
        return GetUpdatesDTO::fromArray($response);
    }
}
