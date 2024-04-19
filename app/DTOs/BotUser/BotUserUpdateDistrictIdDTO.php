<?php

namespace App\DTOs\BotUser;

use App\DTOs\BaseDTO;
use App\Models\BotUser;

class BotUserUpdateDistrictIdDTO extends BaseDTO
{
    public function __construct(public BotUser $user, public int $district_id)
    {
    }
}
