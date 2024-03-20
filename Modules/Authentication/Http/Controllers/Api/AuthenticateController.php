<?php

namespace Modules\Authentication\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\Authentication\Helpers\SMSObject;
use Modules\Authentication\Http\Requests\Api\LoginRequest;
use Modules\Authentication\Transformers\AuthenticateUserResource as UserResource;
use Modules\Core\Http\Controllers\Api\CoreController;
use Modules\LogActivity\Helpers\LogActivity;
use Modules\User\Entities\User;

/**
 * @group  Users management
 *
 * APIs for managing users
 *
 * @Resource("User")
 */
class AuthenticateController extends CoreController
{
    /**
     * Login.
     *
     * Login user using email and password
     *
     * @Post("/login")
     * @Versions({"v1"})
     * @Request({
     *   "login": "you@address.com",
     *   "password": "motdepasse"
     *   })
     */
    public function login(LoginRequest $request)
    {
        if ($request->phone_number) {
            $request->phone_number = str_replace(" ", "", $request->phone_number);
            $user = User::with('antecedents')->where('phone_number', $request->phone_number)->first();

            $credentials = $request->only('phone_number', 'password');

            // check if account exist or password match
            if (!$user || !Auth::attempt($credentials)) {
                throw ValidationException::withMessages([
                    'phone' => __('Invalid phone number or password.'),
                ]);
            }

            // check account status
            if ($user?->status === false) {
                return $this->errorResponse(__('Attempting to login to an inactive user'), []);
            }

            // check phone_number verified status
            if (is_null($user?->phone_number_verified_at)) {
                return $this->errorResponse(__('Connection error, this phone number is not verified'),
                    [],
                    403
                );
            }

            $user->last_login_at = Carbon::now();
            $user->last_login_ip = $request->ip();
            $user->save();

            $token = $user->createToken($request->input('phone_number'))->plainTextToken;
        } else {
            $user = User::with('antecedents')->where('email', strtolower($request->input('email')))->first();

            // check if account exist or password match
            $sanitized = [
                'email' => strtolower($request->input('email')),
                'password' => $request->input('password'),
            ];

            if (empty($user) || !Auth::attempt($sanitized)) {
                throw ValidationException::withMessages([
                    'email' => __('Invalid email address or password.'),
                ]);
            }

            // check account status
            if ($user?->status === false) {
                return $this->errorResponse(__('Attempting to login to an inactive user'), []);
            }

            $user->last_login_at = Carbon::now();
            $user->last_login_ip = $request->ip();
            $user->save();

            $token = $user->createToken($request->input('email'))->plainTextToken;
        }

        $time = now()->toDateTimeString();
        LogActivity::addToLog("Connexion a votre compte le: $time");
        return $this->successResponse(__('Successful connection. Your token was successfully generated'), [
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Logout.
     *
     * Logout
     *
     * @Post("/logout")
     * @Versions({"v1"})
     */
    public function logout(Request $request)
    {
        if ($request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }
        $time = now()->toDateTimeString();
        LogActivity::addToLog("Deconnexion de votre compte le: $time");
        return $this->successResponse(__('Successful logout!'));
    }

    public function otpVerify(Request $request)
    {
        $data = [
            'code' => $request->code,
            'phoneNumber' => $request->phone_number,
        ];

        $response = SMSObject::verifyOTPCode($data['phoneNumber'], $data['code']);

        if (!empty($response)) {
            if($response['verified']){
                return ['message' => 'Phone number verified', 'status'=>true];
            }
        }

        return $this->json(['phone_number' => $data['phoneNumber'], 'error' => 'Invalid verification code entered!', 'status'=>false], 500);
    }

    public function otpSend(Request $request)
    {
        return SMSObject::sendOTPVerificationCode($request->phone_number);
    }
}
