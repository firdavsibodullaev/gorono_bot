<?php

namespace App\Telegram;

use App\Actions\BotUser\BotUserByFromIdChatIdAction;
use App\Actions\District\DistrictsFromNameAction;
use App\Actions\School\SchoolFromNameAction;
use App\DTOs\District\DistrictFromNameDTO;
use App\DTOs\School\SchoolFromNameDTO;
use App\Enums\Language;
use App\Enums\Method;
use App\Models\BotUser;
use App\Modules\Telegram\DTOs\Response\MessageDTO;
use App\Modules\Telegram\Facades\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class Registration extends BaseAction
{
    protected ?BotUser $user;

    public function __construct(protected MessageDTO $message)
    {
        $this->method = Method::SendLanguage;

        parent::__construct($this->message);
        $this->user = BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run();
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

    public function getLanguageSendNameRequest(bool $is_back = false): void
    {
        if (!$is_back) {
            $lang = Language::fromText($this->text);
            if (!$lang) {
                $this->sendLanguage();
                return;
            }

            $this->user->update(['language' => $lang]);

            app()->setLocale($lang->value);
        }

        Request::sendMessage($this->chat_id, __('Familiya ism sharifinigzni kiriting'), reply_markup: Keyboard::back());

        $this->action->set(self::class, Method::GetNameSendBirthdateRequest);
    }

    public function getNameSendBirthdateRequest(bool $is_back = false): void
    {
        if (!$is_back) {
            if (mb_strlen($this->text) > 200) {
                Request::sendMessage($this->chat_id, __('Familiya ism sharifinigzni kiriting'));
                return;
            }

            BackAction::back($this->text, $this->user, fn() => $this->sendLanguage());

            $this->user->update(['name' => $this->text]);
        }

        Request::sendMessage($this->chat_id, __('Tug\'ililgan sananigzni kiriting'), reply_markup: Keyboard::back());

        $this->action->set(self::class, Method::GetBirthdateSendPhoneRequest);
    }

    public function getBirthdateSendPhoneRequest(bool $is_back = false): void
    {
        if (!$is_back) {
            BackAction::back($this->text, $this->user, fn() => $this->getLanguageSendNameRequest(true));

            if (Validator::make(['text' => $this->text], ['text' => 'required|date|date_format:d.m.Y'])->fails()) {
                Request::sendMessage($this->chat_id, __('Tug\'ililgan sananigzni kiriting'));
                return;
            }

            $this->user->update(['birthdate' => Carbon::createFromFormat('d.m.Y', $this->text)]);
        }

        Request::sendMessage($this->chat_id, __('Telefon raqamingizni kiriting'), reply_markup: json_encode([
            'keyboard' => Keyboard::sharePhone(),
            'resize_keyboard' => true,
        ]));

        $this->action->set(self::class, Method::GetPhoneSendDistrictRequest);
    }

    public function getPhoneSendDistrictRequest(bool $is_back = false): void
    {
        if (!$is_back) {
            BackAction::back($this->text, $this->user, fn() => $this->getNameSendBirthdateRequest(true));
            if (!$this->message->contact && !str($this->text)->isMatch('/^\+998-\d{2}-\d{3}(-\d{2}){2}/')) {
                Request::sendMessage($this->chat_id, __('Telefon raqamingizni kiriting'), reply_markup: json_encode([
                    'keyboard' => Keyboard::sharePhone(),
                    'resize_keyboard' => true,
                ]));
                return;
            }

            $this->user->update([
                'phone' => str($this->message->contact?->phone_number ?: $this->text)->replaceMatches('/\D/', '')
            ]);
        }

        Request::sendMessage($this->chat_id, __('Tumaningizni tanlang'), reply_markup: Keyboard::districts($this->user->language));

        $this->action->set(self::class, Method::GetDistrictSendSchoolRequest);
    }

    public function getDistrictSendSchoolRequest(): void
    {
        BackAction::back($this->text, $this->user, fn() => $this->getBirthdateSendPhoneRequest(true));

        $district = DistrictsFromNameAction::make(new DistrictFromNameDTO($this->text))->run();

        if (!$district) {
            $user = BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run();

            Request::sendMessage($this->chat_id, __('Tumaningizni tanlang'), reply_markup: json_encode([
                'keyboard' => Keyboard::districts($user->language),
                'resize_keyboard' => true,
            ]));
            return;
        }

        $this->user->update(['district_id' => $district->id]);

        Request::sendMessage($this->chat_id, __('Maktabingizni tanlang'), reply_markup: json_encode([
            'keyboard' => Keyboard::schools($this->user->district_id, $this->user->language),
            'resize_keyboard' => true,
        ]));

        $this->action->set(self::class, Method::GetSchoolFinishRegistration);
    }

    public function getSchoolFinishRegistration(): void
    {
        BackAction::back($this->text, $this->user, fn() => $this->getPhoneSendDistrictRequest(true));

        $user = BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run();

        $school = SchoolFromNameAction::make(new SchoolFromNameDTO($this->text, $user->district_id))->run();

        if (!$school) {
            Request::sendMessage($this->chat_id, __('Maktabingizni tanlang'), reply_markup: json_encode([
                'keyboard' => Keyboard::schools($user->district_id, $user->language),
                'resize_keyboard' => true,
            ]));
            return;
        }

        $this->user->update([
            'school_id' => $school->id,
            'is_registered' => true
        ]);

        $this->action->clear();

        $this->message->sendMessage(
            text: __("Ro'yhatdan o'tdingiz"),
            reply_markup: Keyboard::remove());

        SendMainMessage::send($this->from_id, $this->chat_id);
    }
}
