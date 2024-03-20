<?php

namespace Modules\Authentication\Http\Controllers\Api;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\Antecedent\Entities\Antecedent;
use Modules\Authentication\Helpers\SMSObject;
use Modules\Authentication\Http\Requests\Api\RegisterRequest;
use Modules\Core\Http\Controllers\Api\CoreController;
use Modules\User\Emails\VerifyEmailMail;
use Modules\User\Entities\User;

/**
 * @group  Users management
 *
 * APIs for managing users
 *
 * @Resource("User")
 */
class RegisterController extends CoreController
{
    /**
     * Register.
     *
     * Register a new user
     *
     * @Post("/register")
     * @Versions({"v1"})
     * @Request({
     *     "name": "Your Name",
     *     "email": "you@address.com",
     *     "password": "motdepasse"
     * })
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email??null,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'birthdate' => $request->birthdate,
            'phone_number' => $request->phone_number ? str_replace(" ", "", $request->phone_number) : null,
        ]);

        $user->assignRole('patient');

        if($request->antecedents) {
            foreach($request->antecedents as $antecedent) {
                $antecedent['user_id'] = $user->id;
                Antecedent::create($antecedent);
            }
        }

        $request->email ? Mail::send(new VerifyEmailMail($user)) : '';
//        SMSObject::sendOTPVerificationCode($request->phone_number);

        return $this->successResponse(
            __('Your user account has been successfully created. A verification email has been sent to you.'),
            []
        );
    }
}
