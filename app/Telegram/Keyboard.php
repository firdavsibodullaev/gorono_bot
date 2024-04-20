<?php

namespace App\Telegram;

use App\Actions\District\DistrictsListAction;
use App\Actions\School\SchoolListAction;
use App\DTOs\School\SchoolListDTO;
use App\Enums\MainMessage;
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

    public static function afterSchoolGoal(): array
    {
        return [
            [
                ['text' => MainMessage::EnterToUniversity->text()],
                ['text' => MainMessage::WantToWork->text()],
            ],
            [
                ['text' => MainMessage::WantToStudyProfession->text()],
                ['text' => MainMessage::WantWorkAbroad->text()],
            ],
            [
                ['text' => MainMessage::IDontKnowYet->text()],
                ['text' => MainMessage::Other->text()],
            ],
        ];
    }

    public static function universityPreparationMethodsList(): array
    {
        return [
            [
                ['text' => __('Maktab bilimlari yetarli')],
                ['text' => __('O‘quv kurslariga boryapman')],
            ],
            [
                ['text' => __('O‘quv kurslariga borishim kerak')],
                ['text' => __('Boshqa')],
            ],
        ];
    }

    public static function universityTypesList(): array
    {
        return [
            [
                ['text' => __('Davlat oliy ta\'lim muassasasiga')],
                ['text' => __('Xususiy oliy ta\'lim muassasasiga')],
            ],
            [
                ['text' => __('Xorijda joylashgan oliy ta\'lim muassasasiga')],
                ['text' => __('Boshqa')],
            ],
        ];
    }
}
