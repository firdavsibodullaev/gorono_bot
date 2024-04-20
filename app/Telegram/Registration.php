<?php

namespace App\Telegram;

use App\Actions\BotUser\BotUserByFromIdChatIdAction;
use App\Actions\BotUser\BotUserUpdateBirthdateAction;
use App\Actions\BotUser\BotUserUpdateDistrictIdAction;
use App\Actions\BotUser\BotUserUpdateLangAction;
use App\Actions\BotUser\BotUserUpdateNameAction;
use App\Actions\BotUser\BotUserUpdatePhoneAction;
use App\Actions\BotUser\BotUserUpdateSchoolIdAction;
use App\Actions\District\DistrictsFromNameAction;
use App\Actions\School\SchoolFromNameAction;
use App\DTOs\BotUser\BotUserUpdateBirthdateDTO;
use App\DTOs\BotUser\BotUserUpdateDistrictIdDTO;
use App\DTOs\BotUser\BotUserUpdateLangDTO;
use App\DTOs\BotUser\BotUserUpdateNameDTO;
use App\DTOs\BotUser\BotUserUpdatePhoneDTO;
use App\DTOs\BotUser\BotUserUpdateSchoolIdDTO;
use App\DTOs\District\DistrictFromNameDTO;
use App\DTOs\School\SchoolFromNameDTO;
use App\Enums\Method;
use App\Modules\Telegram\DTOs\Response\MessageDTO;
use App\Modules\Telegram\Facades\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class Registration extends BaseAction
{
    public function __construct(protected MessageDTO $message)
    {
        $this->method = Method::SendLanguage;

        parent::__construct($this->message);
    }

    public function sendLanguage(): void
    {
        Request::sendMessage($this->chat_id, __('Tilni tanlang'), reply_markup: json_encode([
            'keyboard' => Keyboard::languages(),
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
        ]));

        $this->action->set(self::class, Method::GetLanguageSendNameRequest);
    }

    public function getLanguageSendNameRequest(): void
    {
        if (!in_array($this->text, [__('uz'), __('ru')])) {
            $this->sendLanguage();
            return;
        }

        $lang = [
            __('uz') => 'uz',
            __('ru') => 'ru',
        ][$this->text];

        BotUserUpdateLangAction::make(new BotUserUpdateLangDTO(
            BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run(),
            $lang
        ))->run();

        Request::sendMessage($this->chat_id, __('Familiya ism sharifinigzni kiriting'), reply_markup: json_encode([
            'remove_keyboard' => true
        ]));

        $this->action->set(self::class, Method::GetNameSendBirthdateRequest);
    }

    public function getNameSendBirthdateRequest()
    {
        if (mb_strlen($this->text) > 200) {
            Request::sendMessage($this->chat_id, __('Familiya ism sharifinigzni kiriting'));
            return;
        }

        BotUserUpdateNameAction::make(new BotUserUpdateNameDTO(
            BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run(),
            $this->text
        ))->run();

        Request::sendMessage($this->chat_id, __('Tug\'ililgan sananigzni kiriting'));

        $this->action->set(self::class, Method::GetBirthdateSendPhoneRequest);
    }

    public function getBirthdateSendPhoneRequest(): void
    {
        if (Validator::make(['text' => $this->text], ['text' => 'required|date|date_format:d.m.Y'])->fails()) {
            Request::sendMessage($this->chat_id, __('Tug\'ililgan sananigzni kiriting'));
            return;
        }

        BotUserUpdateBirthdateAction::make(new BotUserUpdateBirthdateDTO(
            BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run(),
            Carbon::createFromFormat('d.m.Y', $this->text)
        ))->run();

        Request::sendMessage($this->chat_id, __('Telefon raqamingizni kiriting'), reply_markup: json_encode([
            'keyboard' => Keyboard::sharePhone(),
            'resize_keyboard' => true,
        ]));

        $this->action->set(self::class, Method::GetPhoneSendDistrictRequest);
    }

    public function getPhoneSendDistrictRequest(): void
    {
        if (!$this->message->contact && !str($this->text)->isMatch('/^\+998\d{9}$/')) {
            Request::sendMessage($this->chat_id, __('Telefon raqamingizni kiriting'), reply_markup: json_encode([
                'keyboard' => Keyboard::sharePhone(),
                'resize_keyboard' => true,
            ]));
            return;
        }

        $user = BotUserUpdatePhoneAction::make(new BotUserUpdatePhoneDTO(
            BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run(),
            $this->message->contact?->phone_number ?: $this->text
        ))->run();

        Request::sendMessage($this->chat_id, __('Tumaningizni tanlang'), reply_markup: json_encode([
            'keyboard' => Keyboard::districts($user->language),
            'resize_keyboard' => true,
        ]));

        $this->action->set(self::class, Method::GetDistrictSendSchoolRequest);
    }

    public function getDistrictSendSchoolRequest(): void
    {
        $district = DistrictsFromNameAction::make(new DistrictFromNameDTO($this->text))->run();

        if (!$district) {
            $user = BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run();

            Request::sendMessage($this->chat_id, __('Tumaningizni tanlang'), reply_markup: json_encode([
                'keyboard' => Keyboard::districts($user->language),
                'resize_keyboard' => true,
            ]));
            return;
        }

        $user = BotUserUpdateDistrictIdAction::make(new BotUserUpdateDistrictIdDTO(
            BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run(),
            $district->id
        ))->run();

        Request::sendMessage($this->chat_id, __('Maktabingizni tanlang'), reply_markup: json_encode([
            'keyboard' => Keyboard::schools($user->district_id, $user->language),
            'resize_keyboard' => true,
        ]));

        $this->action->set(self::class, Method::GetSchoolFinishRegistration);
    }

    public function getSchoolFinishRegistration(): void
    {
        $user = BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run();

        $school = SchoolFromNameAction::make(new SchoolFromNameDTO($this->text, $user->district_id))->run();

        if (!$school) {
            Request::sendMessage($this->chat_id, __('Maktabingizni tanlang'), reply_markup: json_encode([
                'keyboard' => Keyboard::schools($user->district_id, $user->language),
                'resize_keyboard' => true,
            ]));
            return;
        }

        BotUserUpdateSchoolIdAction::make(new BotUserUpdateSchoolIdDTO(
            BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run(),
            $school->id,
            true
        ))->run();

        $this->action->clear();

        SendMainMessage::send($this->from_id, $this->chat_id);
    }
}
