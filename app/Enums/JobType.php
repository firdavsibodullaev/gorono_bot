<?php

namespace App\Enums;

enum JobType: string
{
    case Service = 'service';
    case Manufacture = 'manufacture';
    case Business = 'business';
    case Other = 'other';

    public function text(): string
    {
        return match ($this) {
            self::Service => __('Aholiga xizmat ko\'rsatish'),
            self::Manufacture => __('Ishlab chiqarish'),
            self::Business => __('Xususiy tadbirkorlik'),
            self::Other => __('Boshqa'),
        };
    }

    public static function fromText(?string $text, string $lang): ?JobType
    {
        if (__('Aholiga xizmat ko\'rsatish', locale: $lang) === $text) {
            return self::Service;
        }
        if (__('Ishlab chiqarish', locale: $lang) === $text) {
            return self::Manufacture;
        }

        if (__('Xususiy tadbirkorlik', locale: $lang) === $text) {
            return self::Business;
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
