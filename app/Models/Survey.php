<?php

namespace App\Models;

use App\Enums\MainMessage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property int $bot_user_id
 * @property MainMessage $type
 * @property string $after_school_goal
 * @property string $university_preparation_method
 * @property string $university_type
 * @property string $job_direction
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
        'is_finished',
    ];

    protected function casts(): array
    {
        return [
            'type' => MainMessage::class,
            'is_finished' => 'boolean'
        ];
    }
}
