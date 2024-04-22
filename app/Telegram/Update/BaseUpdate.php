<?php

namespace App\Telegram\Update;

use App\Modules\Telegram\DTOs\Response\UpdateDTO;

abstract class BaseUpdate
{
    public function __construct(protected UpdateDTO $update)
    {
    }

    public static function make(UpdateDTO $update): static
    {
        return new static($update);
    }

    abstract public function index();
}
