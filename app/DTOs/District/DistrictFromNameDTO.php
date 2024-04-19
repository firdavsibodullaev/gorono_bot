<?php

namespace App\DTOs\District;

use App\DTOs\BaseDTO;

class DistrictFromNameDTO extends BaseDTO
{
    public function __construct(public string $name)
    {
    }
}
