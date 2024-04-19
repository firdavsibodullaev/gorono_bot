<?php

namespace App\DTOs\BotUser;

use App\DTOs\BaseDTO;
use App\Models\BotUser;

class BotUserUpdateSchoolIdDTO extends BaseDTO
{
    public function __construct(public BotUser $user, public int $school_id, public ?bool $is_registered = null)
    {
    }
}
