<?php

namespace App\Modules\Telegram\Traits;

trait InteractsWithFileTypes
{
    public function fileType(): string
    {
        return match ($this) {
            self::SendDocument => 'document',
            default => ''
        };
    }
}
