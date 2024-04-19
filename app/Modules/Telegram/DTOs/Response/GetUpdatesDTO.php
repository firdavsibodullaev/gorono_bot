<?php

namespace App\Modules\Telegram\DTOs\Response;

use Illuminate\Support\Collection;

class GetUpdatesDTO
{
    public function __construct(
        public bool       $ok,
        public Collection $result
    )
    {
        $this->result->ensure(UpdateDTO::class);
    }

    public static function fromArray(array $response): static
    {
        $result = collect($response['result'])->map(fn(array $update) => UpdateDTO::fromArray($update));
        return new static(
            ok: $response['ok'],
            result: $result
        );
    }
}
