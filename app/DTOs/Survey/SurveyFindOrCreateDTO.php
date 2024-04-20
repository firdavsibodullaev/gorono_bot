<?php

namespace App\DTOs\Survey;

use App\DTOs\BaseDTO;

class SurveyFindOrCreateDTO extends BaseDTO
{
    public function __construct(
        public int $bot_user_id
    )
    {
    }
}
