<?php

namespace App\Actions\University;

use App\Actions\BaseAction;
use App\Models\University;
use Illuminate\Database\Eloquent\Collection;

class UniversityListAction extends BaseAction
{
    public function run(): Collection
    {
        return University::all();
    }
}
