<?php

namespace App\Models;

use App\Enums\Language;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property string $name_uz
 * @property string $name_ru
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class University extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_uz',
        'name_ru'
    ];

    public function name(Language $language): string
    {
        return $this->{"name_$language->value"} ?? $this->name_uz;
    }
}
