<?php

namespace App\Telegram;

use App\Actions\District\DistrictsListAction;
use App\Actions\School\SchoolListAction;
use App\Actions\University\UniversityListAction;
use App\DTOs\School\SchoolListDTO;
use App\Enums\AfterSchoolGoal;
use App\Enums\BackButton;
use App\Enums\BotUserType;
use App\Enums\JobType;
use App\Enums\Language;
use App\Enums\ProfessionType;
use App\Enums\UniversityPreparationMethod;
use App\Enums\UniversityTypeMethod;
use App\Models\District;
use App\Models\School;
use App\Models\University;
use Illuminate\Database\Eloquent\Collection;

class Keyboard
{
    public static function back(): false|string
    {
        return json_encode([
            'keyboard' => [
                [
                    ['text' => BackButton::Back->text()]
                ]
            ],
            'resize_keyboard' => true,
        ]);
    }

    public static function remove(): string
    {
        return json_encode(['remove_keyboard' => true]);
    }

    public static function languages(): string
    {
        return json_encode([
            'keyboard' => [
                [
                    [
                        'text' => __('uz')
                    ],
                    [
                        'text' => __('ru')
                    ],
                ]
            ],
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
        ]);
    }

    public static function sharePhone(): string
    {
        return json_encode([
            'keyboard' => [
                [
                    ['text' => __('Telefon raqamini ulashish'), 'request_contact' => true]
                ],
                [
                    ['text' => BackButton::Back->text()]
                ],
            ],
            'resize_keyboard' => true,
        ]);
    }

    public static function districts(Language $language): string
    {
        return json_encode([
            'keyboard' => DistrictsListAction::make()
                ->run()
                ->chunk(2)
                ->map(
                    callback: fn(Collection $districts) => $districts->map(
                        callback: fn(District $district) => ['text' => $district->name($language)]
                    )->values()
                )
                ->push([['text' => BackButton::Back->text()]])
                ->values()
                ->toArray(),
            'resize_keyboard' => true
        ]);
    }

    public static function schools(int $district_id, Language $language): string
    {
        return json_encode([
            'keyboard' => SchoolListAction::make(new SchoolListDTO($district_id))
                ->run()
                ->chunk(2)
                ->map(
                    callback: fn(Collection $schools) => $schools->map(
                        callback: fn(School $school) => ['text' => $school->name($language)]
                    )->values()
                )
                ->push([['text' => BackButton::Back->text()]])
                ->values()
                ->toArray(),
            'resize_keyboard' => true,
        ]);
    }

    public static function afterSchoolGoal(): string
    {
        return json_encode([
            'keyboard' => [
                [
                    ['text' => AfterSchoolGoal::EnterToUniversity->text()],
                    ['text' => AfterSchoolGoal::WantToWork->text()],
                ],
                [
                    ['text' => AfterSchoolGoal::WantToStudyProfession->text()],
                    ['text' => AfterSchoolGoal::IDontKnowYet->text()],
                ],
                [
                    ['text' => AfterSchoolGoal::Other->text()],
                ],
            ],
            'resize_keyboard' => true,
        ]);
    }

    public static function universityPreparationMethodsList(): string
    {
        return json_encode([
            'keyboard' => [
                [
                    ['text' => UniversityPreparationMethod::SchoolKnowledge->text()],
                    ['text' => UniversityPreparationMethod::GoToAdditionalCourses->text()],
                ],
                [
                    ['text' => UniversityPreparationMethod::GoToRepetition->text()],
                    ['text' => UniversityPreparationMethod::Other->text()],
                ],
                [
                    ['text' => BackButton::Back->text()],
                ],
            ],
            'resize_keyboard' => true,
        ]);
    }

    public static function universityTypesList(): string
    {
        return json_encode([
            'keyboard' => [
                [
                    ['text' => UniversityTypeMethod::StateUniversity->text()],
                    ['text' => UniversityTypeMethod::PrivateUniversity->text()],
                ],
                [
                    ['text' => UniversityTypeMethod::ForeignUniversity->text()],
                    ['text' => UniversityTypeMethod::Other->text()],
                ],
                [
                    ['text' => BackButton::Back->text()],
                ],
            ],
            'resize_keyboard' => true
        ]);
    }

    public static function jobTypesList(): string
    {
        return json_encode([
            'keyboard' => [
                [
                    ['text' => JobType::Service->text()],
                    ['text' => JobType::Manufacture->text()],
                ],
                [
                    ['text' => JobType::Business->text()],
                    ['text' => JobType::Abroad->text()],
                ],
                [
                    ['text' => JobType::Other->text()],
                ],
                [
                    ['text' => JobType::Back->text()],
                ],
            ],
            'resize_keyboard' => true,
        ]);
    }

    public static function professionTypesList(): string
    {
        return json_encode([
            'keyboard' => [
                [
                    ['text' => ProfessionType::Sewing->text()],
                    ['text' => ProfessionType::Cooking->text()],
                ],
                [
                    ['text' => ProfessionType::Chef->text()],
                    ['text' => ProfessionType::Barber->text()],
                ],
                [
                    ['text' => ProfessionType::Electricity->text()],
                    ['text' => ProfessionType::Accountant->text()],
                ],
                [
                    ['text' => ProfessionType::ItCourses->text()],
                    ['text' => ProfessionType::LanguageCourses->text()],
                ],
                [
                    ['text' => ProfessionType::Other->text()],
                ],
                [
                    ['text' => BackButton::Back->text()],
                ],
            ],
            'resize_keyboard' => true,
        ]);
    }

    public static function botUserType(): string
    {
        return json_encode([
            'keyboard' => [
                [
                    ['text' => BotUserType::School->text()],
                    ['text' => BotUserType::Student->text()],
                ],
                [
                    ['text' => BackButton::Back->text()]
                ]
            ],
            'resize_keyboard' => true,
        ]);
    }

    public static function universities(Language $language): string
    {
        return json_encode([
            'keyboard' => UniversityListAction::make()
                ->run()
                ->chunk(2)
                ->map(
                    callback: fn(Collection $universities) => $universities->map(
                        callback: fn(University $university) => ['text' => $university->name($language)]
                    )->values()
                )
                ->push([['text' => BackButton::Back->text()]])
                ->values()
                ->toArray(),
            'resize_keyboard' => true
        ]);
    }

    public static function postMessageApprove(): string
    {
        return json_encode([
            'keyboard' => [
                [
                    ['text' => __('Tasdiqlash')]
                ],
                [
                    ['text' => BackButton::Back->text()]
                ],
            ],
            'resize_keyboard' => true
        ]);
    }
}
