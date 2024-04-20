<?php

namespace App\Telegram;

use App\Actions\BotUser\BotUserByFromIdChatIdAction;
use App\Actions\Survey\SurveyFindOrCreateAction;
use App\DTOs\Survey\SurveyFindOrCreateDTO;
use App\Enums\MainMessage;
use App\Enums\Method;
use App\Enums\UniversityPreparationMethod;
use App\Enums\UniversityTypeMethod;
use App\Models\BotUser;
use App\Models\Survey;
use App\Modules\Telegram\DTOs\Response\MessageDTO;

class EnterToUniversity extends BaseAction
{
    protected Survey $survey;
    protected BotUser $user;

    public function __construct(protected MessageDTO $message)
    {
        $this->method = Method::SendUniversityPreparationMethodRequest;

        parent::__construct($this->message);

        $this->user = BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run();

        $this->survey = SurveyFindOrCreateAction::make(new SurveyFindOrCreateDTO($this->user->id))->run();
    }

    public function sendUniversityPreparationMethodRequest(): void
    {
        $this->message->sendMessage(
            text: __('Oliy o‘quv yurtlariga topshirish uchun qanday tayyorgarlik ko‘ryapsiz?'),
            reply_markup: json_encode([
                'keyboard' => Keyboard::universityPreparationMethodsList(),
                'resize_keyboard' => true,
            ])
        );

        $this->survey->update([
            'after_school_goal' => $this->text,
            'type' => MainMessage::EnterToUniversity
        ]);

        $this->action->set(static::class, Method::GetUniversityPreparationMethodSendUniversitiesListRequest);
    }

    public function getUniversityPreparationMethodSendUniversitiesListRequest(): void
    {
        $method = UniversityPreparationMethod::fromText($this->text, $this->user->language);

        if (!$method) {
            $this->message->sendMessage(
                text: __('Oliy o‘quv yurtlariga topshirish uchun qanday tayyorgarlik ko‘ryapsiz?'),
                reply_markup: json_encode([
                    'keyboard' => Keyboard::universityPreparationMethodsList(),
                    'resize_keyboard' => true,
                ])
            );

            return;
        }

        if ($method->is(UniversityPreparationMethod::Other)) {
            $this->message->sendMessage(__('Kiriting'), reply_markup: json_encode(['remove_keyboard' => true]));
            $this->action->set(static::class, Method::GetUniversityPreparationMethodOtherSendUniversitiesList);
            return;
        }

        $this->message->sendMessage(
            text: __('Qaysi turdagi oliy ta\'lim muassasasalariga topchirmoqchisiz?'),
            reply_markup: json_encode([
                'keyboard' => Keyboard::universityTypesList(),
                'resize_keyboard' => true
            ])
        );

        $this->survey->update(['university_preparation_method' => $this->text]);

        $this->action->set(static::class, Method::GetUniversityFinishSurveyRequest);
    }

    public function getUniversityPreparationMethodOtherSendUniversitiesListRequest(): void
    {
        if (str($this->text)->length() > 100) {
            $this->message->sendMessage(__('Kiriting'));
        }

        $this->message->sendMessage(
            text: __('Qaysi turdagi oliy ta\'lim muassasasalariga topchirmoqchisiz?'),
            reply_markup: json_encode([
                'keyboard' => Keyboard::universityTypesList(),
                'resize_keyboard' => true
            ])
        );

        $this->survey->update(['university_preparation_method' => $this->text]);

        $this->action->set(static::class, Method::GetUniversityFinishSurveyRequest);
    }

    public function getUniversityFinishSurveyRequest()
    {

        $method = UniversityTypeMethod::fromText($this->text, $this->user->language);

        if (!$method) {
            $this->message->sendMessage(
                text: __('Qaysi turdagi oliy ta\'lim muassasasalariga topchirmoqchisiz?'),
                reply_markup: json_encode([
                    'keyboard' => Keyboard::universityTypesList(),
                    'resize_keyboard' => true
                ])
            );

            return;
        }

        $this->survey->update(['university_type' => $this->text, 'is_finished' => true]);

        $this->action->clear();

        $this->message->sendMessage(__('So\'rovnomada qatnashganingiz uchun raxmat'),
            reply_markup: json_encode(['remove_keyboard' => true]));

        if ($method->is(UniversityTypeMethod::Other)) {
            $this->message->sendMessage(__('Kiriting'), reply_markup: json_encode(['remove_keyboard' => true]));
            $this->action->set(static::class, Method::GetUniversityOtherFinishSurveyRequest);
            return;
        }
    }

    public function getUniversityOtherFinishSurveyRequest(): void
    {
        if (str($this->text)->length() > 100) {
            $this->message->sendMessage(__('Kiriting'));
        }

        $this->survey->update(['university_type' => $this->text, 'is_finished' => true]);

        $this->action->clear();

        $this->message->sendMessage(__('So\'rovnomada qatnashganingiz uchun raxmat'));
    }
}
