<?php

namespace App\Models;

use App\Modules\Telegram\Enums\ChatMemberStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property-read int $id
 * @property int $bot_user_id
 * @property array $file_ids
 * @property array $entities
 * @property string|null $text
 * @property int|null $progress_message_id
 * @property bool $is_ready_for_post
 * @property bool $is_sent
 * @property-read Collection $botUsers
 * @property-read BotUser $creator
 */
class PostMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_user_id',
        'file_ids',
        'text',
        'progress_message_id',
        'entities',
        'is_ready_for_post',
        'is_sent'
    ];

    protected $casts = [
        'file_ids' => 'array',
        'entities' => 'array',
        'is_ready_for_post' => 'boolean',
        'is_sent' => 'boolean',

    ];

    public function botUsers(): BelongsToMany
    {
        return $this->belongsToMany(BotUser::class, 'bot_user_post_message')
            ->withPivot(['is_sent', 'sent_at', 'message_id'])
            ->where('status', ChatMemberStatus::Member)
            ->where('is_registered', true)
            ->using(BotUserPostMessage::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(BotUser::class, 'bot_user_id');
    }
}
