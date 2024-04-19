<?php

namespace App\Actions\School;

use App\Actions\BaseAction;
use App\DTOs\School\SchoolFromNameDTO;
use App\Exceptions\WrongInstanceException;
use App\Models\School;

/**
 * @property-read SchoolFromNameDTO $payload
 */
class SchoolFromNameAction extends BaseAction
{
    /**
     * @throws WrongInstanceException
     */
    public function run(): ?School
    {
        $this->isInstance(SchoolFromNameDTO::class);

        return cache()->remember(
            key: "school-{$this->payload->district_id}-{$this->payload->name}",
            ttl: now()->addDay(),
            callback: fn() => School::query()
                ->whereAny(['name_uz', 'name_ru'], 'like', $this->payload->name)
                ->where('district_id', $this->payload->district_id)
                ->first()
        );
    }
}
