<?php

namespace App\DTOs\School;

use App\DTOs\BaseDTO;

class SchoolListDTO extends BaseDTO
{
    public function __construct(public int $district_id)
    {
    }
}
