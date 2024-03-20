<?php

namespace Modules\Authentication\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\User\Entities\User;

class Authenticate
{
    use AsAction;

    public function handle(Request $request)
    {
        /** @var User $user */
        $user = User::query()->where('email', strtolower($request->input('email')))->first();

        $sanitized = [
            'email' => strtolower($request->input('email')),
            'password' => $request->input('password'),
        ];

        if (empty($user) || ! Auth::attempt($sanitized)) {
            throw ValidationException::withMessages([
                'email' => __('Invalid E-mail address or password.'),
            ]);
        }

        return $user;
    }
}
