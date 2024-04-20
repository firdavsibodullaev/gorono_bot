<?php

namespace App\Telegram;

use App\Enums\BackButton;
use App\Exceptions\StopExecutionException;
use App\Models\BotUser;

class BackAction
{
    public static function back(?string $text, BotUser $user, callable $callback): void
    {
        if (BackButton::fromText($text, $user->language)) {
            $callback();
            throw new StopExecutionException();
        }
    }
}
