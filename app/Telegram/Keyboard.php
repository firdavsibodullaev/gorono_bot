<?php

namespace App\Telegram;

use App\Actions\District\DistrictsListAction;
use App\Actions\School\SchoolListAction;
use App\DTOs\School\SchoolListDTO;
use App\Models\District;
use App\Models\School;
use Illuminate\Database\Eloquent\Collection;

class Keyboard
{
    public static function languages(): array
    {
        return [
            [
                [
                    'text' => __('uz')
                ],
                [
                    'text' => __('ru')
                ],
            ]
        ];
    }

    public static function sharePhone(): array
    {
        return [
            [
                ['text' => __('Telefon raqami bilan ulashish'), 'request_contact' => true]
            ]
        ];
    }

    public static function districts(string $language): array
    {
        return DistrictsListAction::make()
            ->run()
            ->chunk(2)
            ->map(
                callback: fn(Collection $districts) => $districts->map(
                    callback: fn(District $district) => ['text' => $district->name($language)]
                )->values()
            )->values()
            ->toArray();
    }

    public static function schools(int $district_id, string $language)
    {
        return SchoolListAction::make(new SchoolListDTO($district_id))
            ->run()
            ->chunk(2)
            ->map(
                callback: fn(Collection $schools) => $schools->map(
                    callback: fn(School $school) => ['text' => $school->name($language)]
                )->values()
            )->values()
            ->toArray();
    }
}
