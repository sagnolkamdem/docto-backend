<?php

namespace Modules\Authentication\Http\Controllers\Api;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Modules\Authentication\Http\Requests\Api\ResetPasswordRequest;
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
class ResetPasswordController extends CoreController
{
    /**
     * Reset password.
     *
     * Register a new user
     *
     * @Post("/register")
     * @Versions({"v1"})
     * @Request({
     *     "email": "you@address.com",
     *     "password": "motdepasse",
     *     "password_confirmation": "motdepasse",
     *     "token": "token"
     * })
     */
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

        $user = User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        $time = now()->toDateTimeString();
        LogActivity::addToLog("Une reinitialisation de mot de passe a été éffectué le: $time");

        return $this->successResponse(__('Password successfully reset!'), []);
    }
}
