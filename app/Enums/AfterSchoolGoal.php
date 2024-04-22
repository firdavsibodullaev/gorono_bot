<?php

namespace App\Enums;

use App\Telegram\Update\Message\Private\EnterToUniversity;
use App\Telegram\Update\Message\Private\OneStepAnswer;
use App\Telegram\Update\Message\Private\WantToStudyProfession;
use App\Telegram\Update\Message\Private\WantToWork;

enum AfterSchoolGoal: string
{
    case EnterToUniversity = 'enter_to_university';
    case WantToWork = 'want_to_work';
    case WantToStudyProfession = 'want_to_study_profession';
    case WantWorkAbroad = 'want_work_abroad';
    case IDontKnowYet = 'i_dont_know_yet';
    case Other = 'other';

    public static function fromText(?string $text, Language $language): AfterSchoolGoal|false
    {
        $language = $language->value;
        if ($text === null) {
            return false;
        }

        if (__('Oliy ta\'lim muassasasiga kirmoqchiman', locale: $language) === $text) {
            return self::EnterToUniversity;
        }
        if (__('Ishlamoqchiman', locale: $language) === $text) {
            return self::WantToWork;
        }

        if (__('Kasb-hunar o\'rganmoqchiman', locale: $language) === $text) {
            return self::WantToStudyProfession;
        }

        if (__('Xorijda ishlamoqchiman') === $text) {
            return self::WantWorkAbroad;
        }

        if (__('Hali bir qarorga kelganim yo\'q', locale: $language) === $text) {
            return self::IDontKnowYet;
        }

        if (__('Boshqa', locale: $language) === $text) {
            return self::Other;
        }

        return false;
    }

    public function text(): string
    {
        return match ($this) {
            self::EnterToUniversity => __('Oliy ta\'lim muassasasiga kirmoqchiman'),
            self::WantToWork => __('Ishlamoqchiman'),
            self::WantToStudyProfession => __('Kasb-hunar o\'rganmoqchiman'),
            self::WantWorkAbroad => __('Xorijda ishlamoqchiman'),
            self::IDontKnowYet => __('Hali bir qarorga kelganim yo\'q'),
            self::Other => __('Boshqa')
        };
    }

    public function class(): string
    {
        return match ($this) {
            self::EnterToUniversity => EnterToUniversity::class,
            self::WantToWork => WantToWork::class,
            self::WantToStudyProfession => WantToStudyProfession::class,
            self::WantWorkAbroad, self::Other, self::IDontKnowYet => OneStepAnswer::class
        };
    }

    public function is(self $mainMessage): bool
    {
        return $this === $mainMessage;
    }
}
