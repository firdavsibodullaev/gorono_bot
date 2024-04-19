<?php

namespace App\Actions\School;

use App\Actions\BaseAction;
use App\DTOs\School\SchoolListDTO;
use App\Exceptions\WrongInstanceException;
use App\Models\School;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property-read SchoolListDTO $payload
 */
class SchoolListAction extends BaseAction
{
    /**
     * @throws WrongInstanceException
     */
    public function run(): Collection
    {
        $this->isInstance(SchoolListDTO::class);

        return cache()->remember(
            key: "schools-{$this->payload->district_id}",
            ttl: now()->addDay(),
            callback: fn() => School::query()
                ->where('district_id', $this->payload->district_id)
                ->orderBy('id')
                ->get()
        );
    }
}
