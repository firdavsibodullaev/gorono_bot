<?php

namespace App\Enums;

enum ProfessionType: string
{
    case Sewing = 'sewing';
    case Cooking = 'cooking';
    case Chef = 'chef';
    case Barber = 'barber';
    case Electricity = 'electricity';
    case Accountant = 'accountant';
    case ItCourses = 'it_courses';
    case LanguageCourses = 'language_courses';
    case Other = 'other';
    case Back = 'back';

    public function text(): string
    {
        return match ($this) {
            self::Sewing => __('Tikuvchilik'),
            self::Cooking => __('Pazandachilik'),
            self::Chef => __('Oshpazlik'),
            self::Barber => __('Sartaroshlik'),
            self::Electricity => __('Elektromontaj'),
            self::Accountant => __('Buxgalteriya'),
            self::ItCourses => __('IT kurslari'),
            self::LanguageCourses => __('Til kurslari'),
            self::Other => __('Boshqa'),
            self::Back => __('Ortga'),
        };
    }

    public static function fromText(?string $text, string $lang): ?ProfessionType
    {
        if (__('Tikuvchilik', locale: $lang) === $text) {
            return self::Sewing;
        }

        if (__('Pazandachilik', locale: $lang) === $text) {
            return self::Cooking;
        }

        if (__('Oshpazlik', locale: $lang) === $text) {
            return self::Chef;
        }

        if (__('Sartaroshlik', locale: $lang) === $text) {
            return self::Barber;
        }

        if (__('Elektromontaj', locale: $lang) === $text) {
            return self::Electricity;
        }

        if (__('Buxgalteriya', locale: $lang) === $text) {
            return self::Accountant;
        }

        if (__('IT kurslari', locale: $lang) === $text) {
            return self::ItCourses;
        }

        if (__('Til kurslari', locale: $lang) === $text) {
            return self::LanguageCourses;
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
