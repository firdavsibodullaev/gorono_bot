<?php

namespace App\Actions\University;

use App\Actions\BaseAction;
use App\DTOs\University\UniversityFromNameDTO;
use App\Exceptions\WrongInstanceException;
use App\Models\University;

/**
 * @property-read UniversityFromNameDTO $payload
 */
class UniversityFromNameAction extends BaseAction
{
    /**
     * @throws WrongInstanceException
     */
    public function run(): ?University
    {
        $this->isInstance(UniversityFromNameDTO::class);

        return cache()->remember(
            key: "district-{$this->payload->name}",
            ttl: now()->addDay(),
            callback: fn() => University::query()->whereAny(['name_uz', 'name_ru'], 'like', $this->payload->name)->first()
        );
    }
}
