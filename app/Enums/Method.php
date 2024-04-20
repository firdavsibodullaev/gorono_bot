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
    case SendUniversityPreparationMethodRequest = 'sendUniversityPreparationMethodRequest';
    case GetUniversityPreparationMethodSendUniversitiesListRequest = 'getUniversityPreparationMethodSendUniversitiesListRequest';
    case GetUniversityPreparationMethodOtherSendUniversitiesList = 'getUniversityPreparationMethodOtherSendUniversitiesListRequest';
    case GetUniversityFinishSurveyRequest = 'getUniversityFinishSurveyRequest';
    case GetUniversityOtherFinishSurveyRequest = 'getUniversityOtherFinishSurveyRequest';
    case SendProfessionTypesList = 'sendProfessionTypesList';
    case GetProfessionFinishSurvey = 'getProfessionFinishSurvey';
    case GetProfessionOtherFinishSurvey = 'getProfessionOtherFinish';
}
