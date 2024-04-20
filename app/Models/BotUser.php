<?php

namespace App\Models;

use App\Modules\Telegram\Enums\ChatMemberStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property int $from_id
 * @property int $chat_id
 * @property string|null $name
 * @property Carbon $birthdate
 * @property string $phone
 * @property int $district_id
 * @property int $school_id
 * @property ChatMemberStatus $status
 * @property string $language
 * @property bool $is_registered
 * @property-read bool $has_survey
 * @property-read Collection $surveys
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
            'birthdate' => 'date'
        ];
    }

    public function surveys(): HasMany
    {
        return $this->hasMany(Survey::class);
    }

    public function hasSurvey(): Attribute
    {
        $this->load(['surveys' => fn(HasMany $hasMany) => $hasMany->where('is_finished', true)]);

        return Attribute::get(fn() => $this->surveys->isNotEmpty());
    }
}
