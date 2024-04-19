<?php

namespace App\Telegram\Action;

use App\DTOs\Action\ActionDTO;
use App\Enums\Method;
use Illuminate\Support\Carbon;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\SimpleCache\InvalidArgumentException;

class Action
{
    protected string $key;

    public function __construct(protected int $from_id, protected int $chat_id)
    {
        $this->key = "$this->from_id:$this->chat_id";
    }

    public static function make(int $from_id, int $chat_id): static
    {
        return new static($from_id, $chat_id);
    }

    public function get(): ?ActionDTO
    {
        try {
            $position = cache()->get($this->key);

            if (!$position) {
                return null;
            }

            return new ActionDTO(
                class: $position['class'],
                method: $position['method']
            );
        } catch (NotFoundExceptionInterface|ContainerExceptionInterface) {
            return null;
        }
    }

    public function set(string $class, Method $method): bool
    {
        $method = $method->value;

        return cache()->put($this->key, compact('class', 'method'), $this->ttl());
    }

    public function clear(): bool
    {
        try {
            return cache()->delete($this->key);
        } catch (InvalidArgumentException) {
            return false;
        }
    }

    protected function ttl(): Carbon
    {
        return now()->addDay();
    }
}
