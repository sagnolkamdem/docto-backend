<?php

namespace Modules\Authentication\Http\Controllers\Api;

use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Modules\Authentication\Http\Requests\Api\ForgetPasswordRequest;
use Modules\Core\Http\Controllers\Api\CoreController;
use Modules\User\Entities\User;

/**
 * @group  Users management
 *
 * APIs for managing users
 *
 * @Resource("User")
 */
class ForgotPasswordController extends CoreController
{
    /**
     * Forgot password.
     *
     * Request for a new password
     *
     * @Post("/forget-password")
     * @Versions({"v1"})
     * @Request({
        "email": "you@address.com"
        })
     */
    public function forgot(Request $request)
    {
        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        $token = Str::random(64);
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        $user = User::query()->where('email', $request->email)->firstOrFail();
        Mail::send('authentication::forgot-password', ['token' => $token, 'is_admin'=>$user->isAdmin(),'user' => $user, 'logo' => 'img/logo.png'],
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
}
