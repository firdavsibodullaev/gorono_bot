<?php

namespace App\Telegram\Update\Message\Private;

use App\Actions\BotUser\BotUserByFromIdChatIdAction;
use App\Actions\Survey\SurveyFindOrCreateAction;
use App\DTOs\Survey\SurveyFindOrCreateDTO;
use App\Enums\AfterSchoolGoal;
use App\Enums\Method;
use App\Models\BotUser;
use App\Models\Survey;
use App\Modules\Telegram\DTOs\Response\MessageDTO;
use App\Telegram\BackAction;
use App\Telegram\BaseAction;
use App\Telegram\Keyboard;

class OneStepAnswer extends BaseAction
{
    protected Survey $survey;
    protected BotUser $user;

    public function __construct(MessageDTO $message, protected AfterSchoolGoal|bool $mainMessage)
    {
        $this->method = Method::GetOneStepAnswerAndFinish;

        parent::__construct($message);

        $this->user = BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run();

        $this->survey = SurveyFindOrCreateAction::make(new SurveyFindOrCreateDTO($this->user->id))->run();
    }

    public function getOneStepAnswerAndFinish(): void
    {
        $this->survey->update([
            'after_school_goal' => $this->text,
            'type' => $this->mainMessage,
        ]);

        if ($this->mainMessage->is(AfterSchoolGoal::Other)) {
            $this->message->sendMessage(__('Kiriting'), reply_markup: Keyboard::back());
            $this->action->set(static::class, Method::GetOneStepOtherAnswerAndFinish);
            return;
        }

        $this->survey->update([
            'is_finished' => true
        ]);

        $this->action->clear();

        $this->message->sendMessage(__('So\'rovnomada qatnashganingiz uchun raxmat'),
            reply_markup: Keyboard::remove());

        SendMainMessage::send($this->from_id, $this->chat_id);
    }

    public function getOneStepOtherAnswerAndFinish(): void
    {
        BackAction::back($this->text, $this->user, fn() => SendMainMessage::send($this->from_id, $this->chat_id));

        if (str($this->text)->length() > 100) {
            $this->message->sendMessage(__('Kiriting'));
            return;
        }

        $this->survey->update(['job_direction' => $this->text, 'is_finished' => true]);

        $this->action->clear();

        $this->message->sendMessage(__('So\'rovnomada qatnashganingiz uchun raxmat'));

        SendMainMessage::send($this->from_id, $this->chat_id);
    }
}
