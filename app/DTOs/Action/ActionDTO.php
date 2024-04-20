<?php

namespace App\DTOs\Action;

use App\DTOs\BaseDTO;

class ActionDTO extends BaseDTO
{
    public function __construct(
        public ?string $class = null,
        public ?string $method = null
    )
    {
    }
}
