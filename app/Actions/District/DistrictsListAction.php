<?php

namespace App\Actions\District;

use App\Actions\BaseAction;
use App\Models\District;
use Illuminate\Database\Eloquent\Collection;

class DistrictsListAction extends BaseAction
{
    public function run(): Collection
    {
        return cache()->remember('districts', now()->addDay(), function () {
            return District::query()->orderBy('id')->get();
        });
    }
}
