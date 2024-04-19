<?php

namespace App\DTOs\BotUser;

use App\DTOs\BaseDTO;
use App\Models\BotUser;

class BotUserUpdatePhoneDTO extends BaseDTO
{
    public function __construct(public BotUser $user, public string $phone)
    {
    }
}
