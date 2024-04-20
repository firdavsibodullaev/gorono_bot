<?php

namespace App\Telegram;

use App\Actions\BotUser\BotUserByFromIdChatIdAction;
use App\Actions\Survey\SurveyFindOrCreateAction;
use App\DTOs\Survey\SurveyFindOrCreateDTO;
use App\Enums\MainMessage;
use App\Enums\Method;
use App\Enums\ProfessionType;
use App\Models\BotUser;
use App\Models\Survey;
use App\Modules\Telegram\DTOs\Response\MessageDTO;

class WantToWork extends BaseAction
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

    public function sendProfessionTypesList(): void
    {
        $this->message->sendMessage(
            text: __('Qaysi sohada ishlamoqchisiz?'),
            reply_markup: json_encode([
                'keyboard' => Keyboard::professionTypesList(),
                'resize_keyboard' => true,
            ])
        );

        $this->survey->update([
            'after_school_goal' => $this->text,
            'type' => MainMessage::WantToWork
        ]);

        $this->action->set(static::class, Method::GetProfessionFinishSurvey);
    }

    public function getProfessionFinishSurvey()
    {
        $method = ProfessionType::fromText($this->text, $this->user->language);

        if (!$method) {
            $this->message->sendMessage(
                text: __('Qaysi sohada ishlamoqchisiz?'),
                reply_markup: json_encode([
                    'keyboard' => Keyboard::professionTypesList(),
                    'resize_keyboard' => true,
                ])
            );

            return;
        }

        if ($method->is(ProfessionType::Other)) {
            $this->message->sendMessage(__('Kiriting'), reply_markup: json_encode(['remove_keyboard' => true]));
            $this->action->set(static::class, Method::GetProfessionOtherFinishSurvey);
            return;
        }

        $this->survey->update(['job_direction' => $this->text, 'is_finished' => true]);

        $this->action->clear();

        $this->message->sendMessage(
            __('So\'rovnomada qatnashganingiz uchun raxmat'),
            reply_markup: json_encode(['remove_keyboard' => true])
        );
    }

    public function getProfessionOtherFinish(): void
    {
        if (str($this->text)->length() > 100) {
            $this->message->sendMessage(__('Kiriting'));
        }

        $this->survey->update(['job_direction' => $this->text, 'is_finished' => true]);

        $this->action->clear();

        $this->message->sendMessage(__('So\'rovnomada qatnashganingiz uchun raxmat'));
    }
}
