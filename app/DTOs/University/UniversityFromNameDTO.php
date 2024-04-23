<?php

namespace App\DTOs\University;

use App\DTOs\BaseDTO;

class UniversityFromNameDTO extends BaseDTO
{
    public function __construct(public string $name)
    {
    }
}
