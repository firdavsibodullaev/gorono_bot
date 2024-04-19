<?php

namespace App\DTOs\BotUser;

use App\DTOs\BaseDTO;
use App\Models\BotUser;
use Carbon\Carbon;

class BotUserUpdateBirthdateDTO extends BaseDTO
{
    public function __construct(public BotUser $user, public Carbon $birthdate)
    {
    }
}
