<?php

namespace Modules\Authentication\Actions;

use Illuminate\Support\Facades\Hash;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Authentication\Http\Requests\Api\RegisterRequest;
use Modules\User\Entities\User;

class CreateNewUser
{
    use AsAction;

    public function handle(RegisterRequest $request)
    {
        /** @var User $user */
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole(config('modules.authorization.default_role'));

        return $user;
    }
}
