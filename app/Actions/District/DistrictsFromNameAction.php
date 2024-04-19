<?php

namespace App\Actions\District;

use App\Actions\BaseAction;
use App\DTOs\District\DistrictFromNameDTO;
use App\Exceptions\WrongInstanceException;
use App\Models\District;

/**
 * @property-read DistrictFromNameDTO $payload
 */
class DistrictsFromNameAction extends BaseAction
{
    /**
     * @throws WrongInstanceException
     */
    public function run(): ?District
    {
        $this->isInstance(DistrictFromNameDTO::class);

        return cache()->remember(
            key: "district-{$this->payload->name}",
            ttl: now()->addDay(),
            callback: fn() => District::query()->whereAny(['name_uz', 'name_ru'], 'like', $this->payload->name)->first()
        );
    }
}
