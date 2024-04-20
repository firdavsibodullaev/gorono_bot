<?php

namespace App\Enums;

enum Language: string
{
    case Uz = 'uz';
    case Ru = 'ru';

    public static function fromText(?string $text): Language|false
    {
        if ($text === __('uz')) {
            return self::Uz;
        }

        if ($text === __('ru')) {
            return self::Ru;
        }

        return false;
    }
}
