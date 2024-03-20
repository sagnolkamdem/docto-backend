<?php

namespace Modules\Practician\Http\Controllers\Api;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Modules\Authentication\Http\Requests\Api\ForgetPasswordRequest;
use Modules\Authentication\Http\Requests\Api\LoginRequest;
use Modules\Authentication\Http\Requests\Api\ResetPasswordRequest;
use Modules\Core\Http\Controllers\Api\CoreController;
use Modules\LogActivity\Helpers\LogActivity;
use Modules\Practician\Emails\AdminPracticianCreatedMail;
use Modules\Practician\Emails\PracticianCreatedMail;
use Modules\Practician\Entities\Practician;
use Modules\Practician\Http\Requests\RegisterRequest;
use Modules\Practician\Transformers\PracticianResource;
use Modules\User\Entities\User;

class AuthController extends CoreController
{

    public function register(RegisterRequest $request)
    {
        $user = Practician::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'speciality' => $request->speciality,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'emergency' => $request->emergency,
            'head_quarter' => $request->head_quarter,
        ]);

        $user->assignRole('practician');
        if($request->establishment_id){
            DB::table('establishment_practician')->insert([
                'establishment_id' => $request->establishment_id,
                'practician_id' => $user->id
            ]);
        }

        Mail::send(new PracticianCreatedMail($user));
        Mail::send(new AdminPracticianCreatedMail($user));

        return $this->successResponse(
            __('Your user account has been successfully created. A confirmation email has been sent to you.'),
            []
        );
    }

    public function login(LoginRequest $request)
    {
        $user = Practician::query()->where('email', strtolower($request->input('email')))->first();
        if ($user?->is_active === false) {
            return $this->errorResponse(__('Attempting to login to an inactive user'), []);
        }
        $sanitized = [
            'email' => strtolower($request->input('email')),
            'password' => $request->input('password'),
        ];

        if (empty($user) || !Auth::guard('pro')->attempt($sanitized)) {
            throw ValidationException::withMessages([
                'email' => __('Invalid email address or password.'),
            ]);
        }

        $user->last_login_at = Carbon::now();
        $user->last_login_ip = $request->ip();
        $user->save();

        $token = $user->createToken($request->input('email'))->plainTextToken;
        LogActivity::addToLog("Login");
        return $this->successResponse(__('Successful connection. Your token was successfully generated'), [
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => new PracticianResource($user),
        ]);
    }

    public function logout(Request $request)
    {
        if ($request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }

        LogActivity::addToLog("Logout");
        return $this->successResponse(__('Successful logout!'));
    }

    public function forgot(Request $request)
    {
        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        $token = Str::random(64);
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => \Illuminate\Support\Carbon::now()
        ]);

        $user = Practician::query()->where('email', $request->email)->firstOrFail();
        Mail::send('practician::forgot-password', ['token' => $token, 'user' => $user, 'logo' => 'img/logo.png'],
            function($message) use($request){
                $message->to($request->email);
                $message->subject('Reset Password Notification');
                $message->attach('img/logo.png', [
                    'as' => 'logo.png',
                    'mime' => 'image/png',
                ]);

                $message->embedData(file_get_contents('img/logo.png'), 'logo.png', 'image/png');
            }
        );

        return $this->successResponse(__('Password reset e-mail successfully send!'));
    }

    public function reset(ResetPasswordRequest $request)
    {
        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        if(!$updatePassword){
            return $this->errorResponse(__('Password could not be reset!'), ['error' => 'Invalid token']);
        }

        $user = Practician::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        return $this->successResponse(__('Password successfully reset!'), []);
    }
}
