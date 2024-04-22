<?php

namespace App\Telegram;

use App\Actions\BotUser\BotUserByFromIdChatIdAction;
use App\Actions\Survey\SurveyFindOrCreateAction;
use App\DTOs\Survey\SurveyFindOrCreateDTO;
use App\Enums\JobType;
use App\Enums\AfterSchoolGoal;
use App\Enums\Method;
use App\Models\BotUser;
use App\Models\Survey;
use App\Modules\Telegram\DTOs\Response\MessageDTO;

class WantToWork extends BaseAction
{
    protected Survey $survey;
    protected BotUser $user;

    public function __construct(MessageDTO $message)
    {
        $this->method = Method::SendJobTypesList;

        parent::__construct($message);

        $this->user = BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run();

        $this->survey = SurveyFindOrCreateAction::make(new SurveyFindOrCreateDTO($this->user->id))->run();
    }

    public function sendJobTypesList(bool $is_back = false): void
    {
        $this->message->sendMessage(
            text: __('Qaysi sohada ishlamoqchisiz?'),
            reply_markup: json_encode([
                'keyboard' => Keyboard::jobTypesList(),
                'resize_keyboard' => true,
            ])
        );

        if (!$is_back) {
            $this->survey->update([
                'after_school_goal' => $this->text,
                'type' => AfterSchoolGoal::WantToWork
            ]);
        }

        $this->action->set(static::class, Method::GetJobFinishSurvey);
    }

    public function getJobFinishSurvey(): void
    {
        BackAction::back($this->text, $this->user, fn() => SendMainMessage::send($this->from_id, $this->chat_id));

        $method = JobType::fromText($this->text, $this->user->language);

        if (!$method) {
            $this->message->sendMessage(
                text: __('Qaysi sohada ishlamoqchisiz?'),
                reply_markup: json_encode([
                    'keyboard' => Keyboard::jobTypesList(),
                    'resize_keyboard' => true,
                ])
            );

            return;
        }

        if ($method->is(JobType::Other)) {
            $this->message->sendMessage(__('Kiriting'), reply_markup: Keyboard::back());
            $this->action->set(static::class, Method::GetJobOtherFinishSurvey);
            return;
        }

        $this->survey->update(['job_direction' => $this->text, 'is_finished' => true]);

        $this->action->clear();

        $this->message->sendMessage(
            __('So\'rovnomada qatnashganingiz uchun raxmat'),
            reply_markup: Keyboard::remove()
        );
    }

    public function getJobOtherFinish(): void
    {
        BackAction::back($this->text, $this->user, fn() => $this->sendJobTypesList(true));

        if (str($this->text)->length() > 100) {
            $this->message->sendMessage(__('Kiriting'));
            return;
        }

        $this->survey->update(['job_direction' => $this->text, 'is_finished' => true]);

        $this->action->clear();

        $this->message->sendMessage(__('So\'rovnomada qatnashganingiz uchun raxmat'));
    }
}
