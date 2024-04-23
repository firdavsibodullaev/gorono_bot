<?php

namespace App\Enums;

use App\Telegram\Keyboard;

enum BotUserType: string
{
    case School = 'school';
    case Student = 'student';

    public function text(): string
    {
        return match ($this) {
            self::School => __('O\'quvchi'),
            self::Student => __('Talaba'),
        };
    }

    public static function fromText(?string $text, Language $lang): BotUserType|false
    {
        if ($text === __('O\'quvchi', locale: $lang->value)) {
            return self::School;
        }

        if ($text === __('Talaba', locale: $lang->value)) {
            return self::Student;
        }

        return false;
    }

    public function registrationMessageText(): string
    {
        return match ($this) {
            self::School => __('Tumaningizni tanlang'),
            self::Student => __('OTMni tanlang'),
        };
    }

    public function registrationKeyboardButton(Language $language): string
    {
        return match ($this) {
            self::School => Keyboard::districts($language),
            self::Student => Keyboard::universities($language)
        };
    }

    public function registrationActionNextStep(): Method
    {
        return match ($this) {
            self::School => Method::GetDistrictSendSchoolRequest,
            self::Student => Method::GetUniversityFinishRegistrationRequest
        };
    }

    public function is(BotUserType $type): bool
    {
        return $this === $type;
    }
}
