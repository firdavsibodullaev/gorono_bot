<?php

namespace App\Actions;

use App\DTOs\BaseDTO;
use App\Exceptions\WrongInstanceException;

abstract class BaseAction
{
    public function __construct(protected ?BaseDTO $payload = null)
    {
    }

    abstract public function run(): mixed;

    public static function make(?BaseDTO $payload = null): static
    {
        return new static($payload);
    }

    /**
     * @throws WrongInstanceException
     */
    protected function isInstance(string $class): void
    {
        if ($this->payload && !$this->payload instanceof $class) {
            $action_class = get_class($this);

            throw new WrongInstanceException("Instance of $action_class is not instance of $class");
        }
    }
}
