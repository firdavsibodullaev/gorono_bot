<?php

namespace App\Models;

use App\Modules\Telegram\Enums\ChatMemberStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $bot_user_id
 * @property int $post_message_id
 * @property bool $is_sent
 * @property Carbon|null $sent_at
 * @property Carbon|null $message_id
 * @property-read BotUser $botUser
 * @property-read PostMessage $postMessage
 */
class BotUserPostMessage extends Pivot
{
    protected $casts = ['is_sent' => 'boolean', 'sent_at' => 'datetime'];

    public $timestamps = false;

    public function botUser(): BelongsTo
    {
        return $this->belongsTo(BotUser::class)
            ->where('status', ChatMemberStatus::Member)
            ->where('is_registered', true);
    }

    public function postMessage(): BelongsTo
    {
        return $this->belongsTo(PostMessage::class);
    }
}
