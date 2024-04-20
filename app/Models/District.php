<?php

namespace App\Models;

use App\Enums\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property string $name_uz
 * @property string|null $name_ru
 */
class District extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_uz',
        'name_ru',
    ];

    public function name(Language $language)
    {
        return $this->{"name_$language->value"} ?: $this->name_uz;
    }
}
