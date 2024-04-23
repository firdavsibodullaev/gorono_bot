<?php

namespace App\Enums;

enum Method: string
{
    case SendLanguage = 'sendLanguage';
    case GetLanguageSendNameRequest = 'getLanguageSendNameRequest';
    case GetNameSendBirthdateRequest = 'getNameSendBirthdateRequest';
    case GetBirthdateSendPhoneRequest = 'getBirthdateSendPhoneRequest';
    case GetPhoneSendTypeRequest = 'getPhoneSendTypeRequest';
    case GetTypeSendDistrictOrInstituteRequest = 'getTypeSendDistrictOrInstituteRequest';
    case GetDistrictSendSchoolRequest = 'getDistrictSendSchoolRequest';
    case GetSchoolFinishRegistration = 'getSchoolFinishRegistration';
    case SendUniversityPreparationMethodRequest = 'sendUniversityPreparationMethodRequest';
    case GetUniversityPreparationMethodSendUniversitiesListRequest = 'getUniversityPreparationMethodSendUniversitiesListRequest';
    case GetUniversityPreparationMethodOtherSendUniversitiesList = 'getUniversityPreparationMethodOtherSendUniversitiesListRequest';
    case GetUniversityFinishSurveyRequest = 'getUniversityFinishSurveyRequest';
    case GetUniversityOtherFinishSurveyRequest = 'getUniversityOtherFinishSurveyRequest';
    case SendJobTypesList = 'sendJobTypesList';
    case GetJobFinishSurvey = 'getJobFinishSurvey';
    case GetJobOtherFinishSurvey = 'getJobOtherFinish';
    case SendProfessionTypesList = 'sendProfessionTypesList';
    case GetProfessionFinishSurvey = 'getProfessionFinishSurvey';
    case GetProfessionOtherFinishSurvey = 'getProfessionOtherFinish';
    case GetOneStepAnswerAndFinish = 'getOneStepAnswerAndFinish';
    case GetOneStepOtherAnswerAndFinish = 'getOneStepOtherAnswerAndFinish';
    case GetUniversityFinishRegistrationRequest = 'getUniversityFinishRegistrationRequest';
}
