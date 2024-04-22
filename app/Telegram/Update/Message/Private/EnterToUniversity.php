<?php

namespace App\Telegram\Update\Message\Private;

use App\Actions\BotUser\BotUserByFromIdChatIdAction;
use App\Actions\Survey\SurveyFindOrCreateAction;
use App\DTOs\Survey\SurveyFindOrCreateDTO;
use App\Enums\AfterSchoolGoal;
use App\Enums\Method;
use App\Enums\UniversityPreparationMethod;
use App\Enums\UniversityTypeMethod;
use App\Models\BotUser;
use App\Models\Survey;
use App\Modules\Telegram\DTOs\Response\MessageDTO;
use App\Telegram\BackAction;
use App\Telegram\BaseAction;
use App\Telegram\Keyboard;

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

    public function sendUniversityPreparationMethodRequest(bool $is_back = false): void
    {
        $this->message->sendMessage(
            text: __('Oliy o‘quv yurtlariga topshirish uchun qanday tayyorgarlik ko‘ryapsiz?'),
            reply_markup: Keyboard::universityPreparationMethodsList()
        );

        if (!$is_back) {
            $this->survey->update([
                'after_school_goal' => $this->text,
                'type' => AfterSchoolGoal::EnterToUniversity
            ]);
        }

        $this->action->set(static::class, Method::GetUniversityPreparationMethodSendUniversitiesListRequest);
    }

    public function getUniversityPreparationMethodSendUniversitiesListRequest(): void
    {
        BackAction::back($this->text, $this->user, fn() => SendMainMessage::send($this->from_id, $this->chat_id));

        $method = UniversityPreparationMethod::fromText($this->text, $this->user->language);

        if (!$method) {
            $this->message->sendMessage(
                text: __('Oliy o‘quv yurtlariga topshirish uchun qanday tayyorgarlik ko‘ryapsiz?'),
                reply_markup: Keyboard::universityPreparationMethodsList()
            );

            return;
        }

        if ($method->is(UniversityPreparationMethod::Other)) {
            $this->message->sendMessage(__('Kiriting'), reply_markup: Keyboard::back());
            $this->action->set(static::class, Method::GetUniversityPreparationMethodOtherSendUniversitiesList);
            return;
        }

        $this->message->sendMessage(
            text: __('Qaysi turdagi oliy ta\'lim muassasasalariga topchirmoqchisiz?'),
            reply_markup: Keyboard::universityTypesList()
        );

        $this->survey->update(['university_preparation_method' => $this->text]);

        $this->action->set(static::class, Method::GetUniversityFinishSurveyRequest);
    }

    public function getUniversityPreparationMethodOtherSendUniversitiesListRequest(bool $is_back = false): void
    {
        if (!$is_back) {
            BackAction::back($this->text, $this->user, fn() => $this->sendUniversityPreparationMethodRequest(true));

            if (str($this->text)->length() > 100) {
                $this->message->sendMessage(__('Kiriting'));
                return;
            }

            $this->survey->update(['university_preparation_method' => $this->text]);
        }

        $this->message->sendMessage(
            text: __('Qaysi turdagi oliy ta\'lim muassasasalariga topchirmoqchisiz?'),
            reply_markup: Keyboard::universityTypesList()
        );


        $this->action->set(static::class, Method::GetUniversityFinishSurveyRequest);
    }

    public function getUniversityFinishSurveyRequest(): void
    {
        BackAction::back($this->text, $this->user, fn() => $this->sendUniversityPreparationMethodRequest(true));

        $method = UniversityTypeMethod::fromText($this->text, $this->user->language);

        if (!$method) {
            $this->message->sendMessage(
                text: __('Qaysi turdagi oliy ta\'lim muassasasalariga topchirmoqchisiz?'),
                reply_markup: Keyboard::universityTypesList()
            );

            return;
        }

        if ($method->is(UniversityTypeMethod::Other)) {
            $this->message->sendMessage(__('Kiriting'), reply_markup: Keyboard::back());
            $this->action->set(static::class, Method::GetUniversityOtherFinishSurveyRequest);
            return;
        }

        $this->survey->update(['university_type' => $this->text, 'is_finished' => true]);

        $this->action->clear();

        $this->message->sendMessage(__('So\'rovnomada qatnashganingiz uchun raxmat'),
            reply_markup: Keyboard::remove());

    }

    public function getUniversityOtherFinishSurveyRequest(): void
    {
        BackAction::back($this->text, $this->user, fn() => $this->getUniversityPreparationMethodOtherSendUniversitiesListRequest(true));

        if (str($this->text)->length() > 100) {
            $this->message->sendMessage(__('Kiriting'));
            return;
        }

        $this->survey->update(['university_type' => $this->text, 'is_finished' => true]);

        $this->action->clear();

        $this->message->sendMessage(__('So\'rovnomada qatnashganingiz uchun raxmat'));
    }
}
