<?php

namespace App\Telegram\Update\Message\Private;

use App\Actions\BotUser\BotUserByFromIdChatIdAction;
use App\Actions\Survey\SurveyFindOrCreateAction;
use App\DTOs\Survey\SurveyFindOrCreateDTO;
use App\Enums\AfterSchoolGoal;
use App\Enums\Method;
use App\Enums\ProfessionType;
use App\Models\BotUser;
use App\Models\Survey;
use App\Modules\Telegram\DTOs\Response\MessageDTO;
use App\Telegram\BackAction;
use App\Telegram\BaseAction;
use App\Telegram\Keyboard;

class WantToStudyProfession extends BaseAction
{
    protected Survey $survey;
    protected BotUser $user;

    public function __construct(MessageDTO $message)
    {
        $this->method = Method::SendProfessionTypesList;

        parent::__construct($message);

        $this->user = BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run();

        $this->survey = SurveyFindOrCreateAction::make(new SurveyFindOrCreateDTO($this->user->id))->run();
    }

    public function sendProfessionTypesList(bool $is_back = false): void
    {
        $this->message->sendMessage(
            text: __('Qaysi yo\'nalishda kasb-hunar egallashni istaysiz?'),
            reply_markup: Keyboard::professionTypesList()
        );

        if (!$is_back) {
            $this->survey->update([
                'after_school_goal' => $this->text,
                'type' => AfterSchoolGoal::WantToWork
            ]);
        }

        $this->action->set(static::class, Method::GetProfessionFinishSurvey);
    }

    public function getProfessionFinishSurvey(): void
    {
        BackAction::back($this->text, $this->user, fn() => SendMainMessage::send($this->from_id, $this->chat_id));

        $method = ProfessionType::fromText($this->text, $this->user->language);

        if (!$method) {
            $this->message->sendMessage(
                text: __('Qaysi yo\'nalishda kasb-hunar egallashni istaysiz?'),
                reply_markup: Keyboard::professionTypesList()
            );

            return;
        }

        if ($method->is(ProfessionType::Other)) {
            $this->message->sendMessage(__('Kiriting'), reply_markup: Keyboard::back());
            $this->action->set(static::class, Method::GetProfessionOtherFinishSurvey);
            return;
        }

        $this->survey->update(['job_direction' => $this->text, 'is_finished' => true]);

        $this->action->clear();

        $this->message->sendMessage(
            __('So\'rovnomada qatnashganingiz uchun raxmat'),
            reply_markup: Keyboard::remove()
        );
    }

    public function getProfessionOtherFinish(): void
    {
        BackAction::back($this->text, $this->user, fn() => $this->sendProfessionTypesList(true));

        if (str($this->text)->length() > 100) {
            $this->message->sendMessage(__('Kiriting'), reply_markup: Keyboard::back());
            return;
        }

        $this->survey->update(['job_direction' => $this->text, 'is_finished' => true]);

        $this->action->clear();

        $this->message->sendMessage(__('So\'rovnomada qatnashganingiz uchun raxmat'));
    }
}
