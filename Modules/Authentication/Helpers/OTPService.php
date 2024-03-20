<?php

namespace Modules\Authentication\Helpers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\App;
use Modules\User\Entities\User;

class OTPService
{
    public $endpoint;
    public $username;
    public $api_key;

    public function __construct()
    {
        $this->endpoint = config('app.clicksend_endpoint') . "/sms/send";
        $this->username = config('app.clicksend_username');
        $this->api_key = config('app.clicksend_api_key');
    }

    /**
     * Generate a random OTP
     * @return int
     */
    public function generateOTP(): int
    {
        return (App::environment('staging','production')) ? rand(100000, 999999) : '555555';
    }

    /**
     * Send an OTP to the user via ClickSend
     * @param $phone_number
     * @return int
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendOTP($phone_number)
    {
        // Generate an OTP
        $otp = $this->generateOTP();
        $user = User::query()->where('phone_number','like', '%'. $phone_number. '%')->firstOrFail();

        // Create the message body
        $message = $otp . " is your Tabiblib OTP Code";

        $user->otp_code = $otp;
        $user->save();

        // Send the SMS via ClickSend if staging|production
        if(App::environment('staging','production')) {
            $client = new Client();
            $response = $client->post($this->endpoint, [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($this->username . ':' . $this->api_key),
                ],
                'form_params' => [
                    'messages' => [
                        [
                            'to' => $phone_number,
                            'body' => $message,
                        ]
                    ],
                ],
            ]);
            // Check for errors
            if ($response->getStatusCode() != 200) {
                throw new \Exception("Failed to send SMS via ClickSend");
            }
        }
        // Return the OTP sent
        return $otp;
    }
}
