<?php

namespace App\Enums;

enum UniversityPreparationMethod: string
{
    case SchoolKnowledge = 'school_knowledge';
    case GoToRepetition = 'go_to_repetition';
    case GoToAdditionalCourses = 'go_to_additional_courses';
    case Other = 'other';

    public function text(): string
    {
        return match ($this) {
            self::SchoolKnowledge => __('Maktab bilimlari yetarli'),
            self::GoToRepetition => __('O‘quv kurslariga borishim kerak'),
            self::GoToAdditionalCourses => __('O‘quv kurslariga boryapman'),
            self::Other => __('Boshqa'),
        };
    }

    public static function fromText(?string $text, string $lang): ?UniversityPreparationMethod
    {
        if (__('Maktab bilimlari yetarli', locale: $lang) === $text) {
            return self::SchoolKnowledge;
        }
        if (__('O‘quv kurslariga borishim kerak', locale: $lang) === $text) {
            return self::GoToRepetition;
        }

        if (__('O‘quv kurslariga boryapman', locale: $lang) === $text) {
            return self::GoToAdditionalCourses;
        }
        if (__('Boshqa', locale: $lang) === $text) {
            return self::Other;
        }

        return null;
    }

    public function is(self $method): bool
    {
        return $this === $method;
    }
}
