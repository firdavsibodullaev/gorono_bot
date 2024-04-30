<?php

namespace App\Models;

use App\Enums\BotUserType;
use App\Enums\Language;
use App\Modules\Telegram\Enums\ChatMemberStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Context;
use Throwable;

/**
 * @property-read int $id
 * @property int $from_id
 * @property int $chat_id
 * @property string|null $name
 * @property BotUserType|null $type
 * @property Carbon $birthdate
 * @property string $phone
 * @property int $district_id
 * @property int $school_id
 * @property int $university_id
 * @property ChatMemberStatus $status
 * @property Language $language
 * @property bool $is_registered
 * @property-read bool $has_survey
 * @property-read Collection $surveys
 * @property-read District $district
 * @property-read School $school
 * @property-read University $university
 * @property-read string $phone_formatted
 */
class BotUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_id',
        'chat_id',
        'name',
        'type',
        'birthdate',
        'phone',
        'district_id',
        'school_id',
        'university_id',
        'status',
        'language',
        'is_registered',
    ];

    public static function member(): Builder
    {
        return static::query()->where('status', ChatMemberStatus::Member);
    }

    protected function casts(): array
    {
        return [
            'status' => ChatMemberStatus::class,
            'is_registered' => 'boolean',
            'birthdate' => 'date',
            'language' => Language::class,
            'type' => BotUserType::class
        ];
    }

    public function surveys(): HasMany
    {
        return $this->hasMany(Survey::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function hasSurvey(): Attribute
    {
        $this->load(['surveys' => fn(HasMany $hasMany) => $hasMany->where('is_finished', true)]);

        return Attribute::get(fn() => $this->surveys->isNotEmpty());
    }

    public function phoneFormatted(): Attribute
    {
        try {
            $phone = sprintf("+%d%d%d%d%d-%d%d%d-%d%d-%d%d", ...str_split($this->phone));
        } catch (Throwable $e) {

            Context::add([
                'id' => $this->id,
                'phone' => $this->phone,
                'error' => $e->getMessage(),
            ]);

            $phone = "+$this->phone";
        }
        return Attribute::get(fn() => $phone);
    }
}
