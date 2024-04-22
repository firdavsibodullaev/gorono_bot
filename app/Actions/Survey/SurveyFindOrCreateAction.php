<?php

namespace App\Actions\Survey;

use App\Actions\BaseAction;
use App\DTOs\Survey\SurveyFindOrCreateDTO;
use App\Enums\Language;
use App\Models\Survey;

/**
 * @property-read SurveyFindOrCreateDTO $payload
 */
class SurveyFindOrCreateAction extends BaseAction
{

    public function run(): Survey
    {
        /** @var Survey $survey */
        $survey = Survey::query()->firstOrCreate([
            'bot_user_id' => $this->payload->bot_user_id,
            'is_finished' => false
        ], [
            'language' => Language::tryFrom(app()->getLocale())
        ]);

        return $survey;
    }
}
