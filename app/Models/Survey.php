<?php

namespace App\Models;

use App\Enums\AfterSchoolGoal;
use App\Enums\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property int $bot_user_id
 * @property AfterSchoolGoal $type
 * @property string $after_school_goal
 * @property string $university_preparation_method
 * @property string $university_type
 * @property string $job_direction
 * @property Language $language
 * @property boolean $is_finished
 */
class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_user_id',
        'type',
        'after_school_goal',
        'university_preparation_method',
        'university_type',
        'job_direction',
        'language',
        'is_finished',
    ];

    protected function casts(): array
    {
        return [
            'type' => AfterSchoolGoal::class,
            'is_finished' => 'boolean',
            'language' => Language::class
        ];
    }
}
