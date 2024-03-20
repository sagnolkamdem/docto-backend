<?php

namespace Modules\User\Http\Controllers\Api;

use Dingo\Api\Auth\Auth;
use Dingo\Api\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Modules\Antecedent\Entities\Antecedent;
use Modules\Authentication\Transformers\AuthenticateUserResource;
use Modules\Core\Http\Controllers\Api\CoreController;
use Modules\LogActivity\Helpers\LogActivity;
use Modules\Practician\Entities\Practician;
use Modules\User\Entities\User;
use Modules\User\Http\Requests\Api\FindUserRequest;
use Modules\User\Http\Requests\Api\UpdateUserRequest;
use Modules\User\Transformers\ProfilePatientResource;

/**
 * @group  User account
 *
 * APIs for user informations and update profile
 *
 * @Resource("User", uri="/user")
 */
class UserController extends CoreController
{
    public function me(Request $request)
    {
        return $this->successResponse(__('User information'), [
            'user' => new AuthenticateUserResource($request->user()),
        ]);
    }

    public function exists(Request $request)
    {
        if($request->phone_number) {
            $user = User::query()->where('phone_number', $request->phone_number)->first();
            if($user) {
                return $this->successResponse(__('User exists'), [
                    'user' => new AuthenticateUserResource($user),
                ]);
            }

            return $this->errorResponse(__('User does not exist with this phone number'));
        }

        if($request->email) {
            $user = User::query()->where('email', $request->email)->first();
            if($user) {
                return $this->successResponse(__('User exists'), [
                    'user' => new AuthenticateUserResource($user),
                ]);
            }

            return $this->errorResponse(__('User does not exist with this email'));
        }

        return $this->errorResponse(__('Pass either the phone number or the email to check if the user exists'));
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        $user = User::findOrFail($id);
        $path = null;
        if($request->hasFile('profile_image')){

            $file = $request->file('profile_image');

            $name = now()->timestamp.".{$file->getClientOriginalName()}";

            $path = config('app.url').'/storage/'.$file->storeAs('profile', $name, 'public');
        }
        $data = array_filter([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'gender' => $request->gender,
            'birthdate' => $request->birthdate,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'height' => $request->height,
            'weight' => $request->weight,
            'status' => $request->status??$user->status,
            'profile_photo_url' => $path,
        ]);
        $user->update($data);

        if($request->antecedents) {
            DB::table('antecedents')->where('user_id', $id)->delete();

            foreach($request->antecedents as $antecedent) {
                $antecedent['user_id'] = $user->id;
                Antecedent::create($antecedent);
            }
        }

        $time = now()->toDateTimeString();
        LogActivity::addToLog("Vos informations personnelles ont été mis a jour le: $time");
        return $this->successResponse(
            __('Update user successfully'),
            ['user' => new AuthenticateUserResource($user)]
        );
    }

    public function updatePreferences(FindUserRequest $request, int $id) {
        $user = User::findOrFail($id);
        $data = array_filter([
            'timezone' => $request->timezone,
            'language' => $request->language,
        ]);
        $user->update($data);

        return $this->successResponse(
            __('Update user successfully'),
            ['user' => new AuthenticateUserResource($user)]
        );
    }

    public function updatePassword(FindUserRequest $request) {
        $id = $request->user()->id;
        $user = User::findOrFail($id);

        if (Hash::check($request->password, $user->password)) {
            $data = [
                'password' => Hash::make($request->new_password),
            ];
            $user->update($data);

            $time = now()->toDateTimeString();
            LogActivity::addToLog("Votre mot de passe a été mis a jour le: $time");
            return $this->successResponse(
                __('Update user password successfully'),
                ['user' => new AuthenticateUserResource($user)]
            );
        }
        return $this->errorResponse(
            __('Error updating password, old password incorrect'),
            ['user' => new AuthenticateUserResource($user)],
            500
        );
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

//        $this->authorize(Policy::DELETE, $speciality);

        $user->delete();

        return $this->successResponse(__('Deleted user successfully!'));
    }

    public function destroyPractician($id)
    {
        $user = Practician::findOrFail($id);

//        $this->authorize(Policy::DELETE, $speciality);

        $user->delete();

        return $this->successResponse(__('Deleted user successfully!'));
    }
}
