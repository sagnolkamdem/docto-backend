<?php

namespace Modules\Authentication\Http\Controllers\Api;

use Dingo\Api\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Modules\Authentication\Http\Requests\Api\EmailVerificationRequest;
use Modules\Core\Http\Controllers\Api\CoreController;
use Modules\User\Emails\VerifyEmailMail;
use Modules\User\Entities\User;

class VerifyEmailController extends CoreController
{
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function __invoke(EmailVerificationRequest $request, $id)
    {
        $id = Crypt::decryptString($id);

        $user = User::findOrFail($id);

        if ($user->hasVerifiedEmail()) {
            return view('authentication::emailverified')->with([
                'user' => $user,
                'is_admin'=>$user->isAdmin()
            ]);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return view('authentication::emailverified')->with([
            'user' => $user,
            'is_admin'=>$user->isAdmin()
        ]);
    }

    public function resendEmail(EmailVerificationRequest $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->hasVerifiedEmail()) {
            return view('authentication::emailverified')->with([
                'user' => $user,
                'is_admin'=>$user->isAdmin()
            ]);
        }

        Mail::send(new VerifyEmailMail($user));

        return $this->successResponse(
            __('A verification email has been sent to you.'),
            ['user' => $user,'is_admin'=>$user->isAdmin()]
        );
    }
}
