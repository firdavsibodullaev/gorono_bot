<?php

namespace App\Models;

use App\Modules\Telegram\Enums\ChatMemberStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property int $from_id
 * @property int $chat_id
 * @property string|null $name
 * @property string $birthdate
 * @property string $phone
 * @property int $district_id
 * @property int $school_id
 * @property ChatMemberStatus $status
 * @property string $language
 * @property bool $is_registered
 */
class BotUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_id',
        'chat_id',
        'name',
        'birthdate',
        'phone',
        'district_id',
        'school_id',
        'status',
        'language',
        'is_registered',
    ];

    protected function casts()
    {
        return [
            'status' => ChatMemberStatus::class,
            'is_registered' => 'boolean',
        ];
    }
}
