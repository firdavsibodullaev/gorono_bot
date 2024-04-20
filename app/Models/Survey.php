<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property int $bot_user_id
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
        'after_school_goal',
        'university_preparation_method',
        'university_type',
        'job_direction',
        'is_finished',
    ];

    protected function casts(): array
    {
        return ['is_finished' => 'boolean'];
    }
}
