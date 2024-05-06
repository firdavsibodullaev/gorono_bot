<?php

namespace App\Models;

use App\Enums\BotUserPostMessageStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $id
 * @property int $bot_user_id
 * @property int $post_message_id
 * @property BotUserPostMessageStatus $status
 * @property Carbon|null $sent_at
 * @property Carbon|null $message_id
 * @property-read BotUser $botUser
 * @property-read PostMessage $postMessage
 */
class BotUserPostMessage extends Pivot
{
    protected $casts = [
        'status' => BotUserPostMessageStatus::class,
        'sent_at' => 'datetime'
    ];

    public $timestamps = false;

    public function botUser(): BelongsTo
    {
        return $this->belongsTo(BotUser::class)->where('is_registered', true);
    }

    public function postMessage(): BelongsTo
    {
        return $this->belongsTo(PostMessage::class);
    }
}
