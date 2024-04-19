<?php

namespace App\Modules\Telegram\DTOs\Request;

class GetUpdatesDTO extends BaseDTO
{
    public function __construct(
        public ?int   $offset = null,
        public ?int   $limit = null,
        public ?int   $timeout = null,
        public ?array $allowed_updates = null,
    )
    {
        $this->container = get_defined_vars();
    }
}
