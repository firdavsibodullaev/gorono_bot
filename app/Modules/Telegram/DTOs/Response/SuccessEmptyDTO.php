<?php

namespace App\Modules\Telegram\DTOs\Response;

class SuccessEmptyDTO
{
    public function __construct(
        public bool $ok,
        public bool $result,
    )
    {
    }
}
