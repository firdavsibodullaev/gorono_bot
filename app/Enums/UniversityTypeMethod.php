<?php

namespace App\Enums;

enum UniversityTypeMethod: string
{
    case StateUniversity = 'state_university';
    case PrivateUniversity = 'private_university';
    case ForeignUniversity = 'foreign_university';
    case Other = 'other';
    case Back = 'back';

    public function text(): string
    {
        return match ($this) {
            self::StateUniversity => __('Davlat oliy ta\'lim muassasasiga'),
            self::PrivateUniversity => __('Xususiy oliy ta\'lim muassasasiga'),
            self::ForeignUniversity => __('Xorijda joylashgan oliy ta\'lim muassasasiga'),
            self::Other => __('Boshqa'),
            self::Back => __('Ortga'),
        };
    }

    public static function fromText(?string $text, Language $lang): ?UniversityTypeMethod
    {
        $lang = $lang->value;
        if (__('Davlat oliy ta\'lim muassasasiga', locale: $lang) === $text) {
            return self::StateUniversity;
        }

        if (__('Xususiy oliy ta\'lim muassasasiga', locale: $lang) === $text) {
            return self::PrivateUniversity;
        }

        if (__('Xorijda joylashgan oliy ta\'lim muassasasiga', locale: $lang) === $text) {
            return self::ForeignUniversity;
        }

        if (__('Boshqa', locale: $lang) === $text) {
            return self::Other;
        }

        if (__('Ortga', locale: $lang) === $text) {
            return self::Back;
        }

        return null;
    }

    public function is(self $method): bool
    {
        return $this === $method;
    }
}
