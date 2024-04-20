<?php

namespace App\Enums;

enum BackButton: string
{
    case Back = 'back';

    public function text(): string
    {
        return match ($this) {
            self::Back => __('Ortga')
        };
    }

    public static function fromText(?string $text, Language $lang): ?self
    {
        if (__('Ortga', locale: $lang->value) === $text) {
            return self::Back;
        }

        return null;
    }
}
