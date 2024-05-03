<?php

namespace App\Modules\Telegram\Exceptions;

class TooManyTimesException extends BaseException
{
    protected int $retry_after;

    public function setRetryAfter(int $retry_after): static
    {
        $this->retry_after = $retry_after;

        return $this;
    }

    public function getRetryAfter(): int
    {
        return $this->retry_after;
    }
}
