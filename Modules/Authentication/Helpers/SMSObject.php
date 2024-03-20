<?php

namespace Modules\Authentication\Helpers;

use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Modules\User\Entities\User;

class SMSObject
{

    public static function sendMessage(string $message, string $to)
    {
        $client = new Client();
        $otp_service = new OTPService();
        $response = $client->post($otp_service->endpoint, [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($otp_service->username . ':' . $otp_service->api_key),
            ],

            'form_params' => [
                'messages' => [
                    [
                        'to' => $to,
                        'body' => $message,
                    ],
                ],
            ],
        ]);
        return json_decode($response->getBody()->getContents());
    }

    public static function sendOTPVerificationCode(string $phone_number)
    {
        $otp_service = new OTPService();
        $otp = $otp_service->sendOTP($phone_number);

        return ['message' => 'ok', 'response' => $otp, 'phone_number' => $phone_number];
    }

    public static function verifyOTPCode(string $phone_number, $otp)
    {
        $user = User::query()->where('phone_number','like', '%'. $phone_number. '%')->firstOrFail();

        $userEnteredOtp = $otp;
        $storedOtpCode = $user->otp_code;
        if ($storedOtpCode == $userEnteredOtp) {
            $user->phone_number_verified_at = now();
            $user->otp_code = NULL;
            $user->save();
            return ['message' => 'ok', 'response' => $otp, 'phone_number' => $phone_number, 'verified' => true];
        }

        return ['message' => 'error', 'response' => $otp, 'phone_number' => $phone_number, 'verified' => false];
    }
}
