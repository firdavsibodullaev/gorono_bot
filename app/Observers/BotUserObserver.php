<?php

namespace App\Observers;

use App\Models\BotUser;

class BotUserObserver
{
    public function updated(BotUser $user): void
    {
        cache()->put(
            key: "bot-user-$user->from_id-$user->chat_id",
            value: $user,
            ttl: now()->addDay()
        );
    }

    public function deleted(BotUser $user): void
    {
        cache()->forget("bot-user-$user->from_id-$user->chat_id");
    }
}
