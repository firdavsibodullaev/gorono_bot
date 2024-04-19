<?php

namespace App\Enums;

enum Method: string
{
    case SendLanguage = 'sendLanguage';
    case GetLanguageSendNameRequest = 'getLanguageSendNameRequest';
    case GetNameSendBirthdateRequest = 'getNameSendBirthdateRequest';
    case GetBirthdateSendPhoneRequest = 'getBirthdateSendPhoneRequest';
    case GetPhoneSendDistrictRequest = 'getPhoneSendDistrictRequest';
    case GetDistrictSendSchoolRequest = 'getDistrictSendSchoolRequest';
    case GetSchoolFinishRegistration = 'getSchoolFinishRegistration';
}
