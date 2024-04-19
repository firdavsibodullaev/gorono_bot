<?php

namespace App\DTOs\School;

use App\DTOs\BaseDTO;

class SchoolFromNameDTO extends BaseDTO
{
    public function __construct(public string $name, public int $district_id)
    {
    }
}
