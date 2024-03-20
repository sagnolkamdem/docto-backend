<?php

namespace Modules\Practician\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Http\Controllers\Api\CoreController;
use Modules\Establishment\Entities\Establishment;
use Modules\Practician\Emails\AdminPracticianCreatedMail;
use Modules\Practician\Emails\PracticianCreatedMail;
use Modules\Practician\Entities\Practician;
use Modules\Practician\Http\Requests\RegisterRequest;
use Modules\Practician\Transformers\PracticianResource;
use Modules\Practician\Transformers\PracticianResourceCollection;
use Modules\User\Http\Requests\Api\FindUserRequest;
use Modules\User\Http\Requests\Api\UpdateUserRequest;

class PracticianController extends CoreController
{

    public function me(Request $request)
    {
        return $this->successResponse(__('User information'), [
            'practician' => new PracticianResource($request->user()),
        ]);
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        $practician = Practician::findOrFail($id);
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
            'speciality' => $request->speciality,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'profile_photo_url' => $path,
            'birthdate' => $request->birthdate,
            'accepts_new_patients' => $request->accepts_new_patients,
            'presentation' => $request->presentation,
            'expertises' => $request->expertises,
            'emergency' => $request->emergency,
            'head_quarter' => $request->head_quarter,
        ]);
        $practician->update($data);

        return $this->successResponse(
            __('Update practician successfully'),
            ['practician' => new PracticianResource($practician)]
        );
    }

    public function updatePassword(FindUserRequest $request) {
        $id = $request->user()->id;
        $practician = Practician::findOrFail($id);

        if (Hash::check($request->password, $practician->password)) {
            $data = [
                'password' => Hash::make($request->new_password),
            ];
            $practician->update($data);

            return $this->successResponse(
                __('Update practician password successfully'),
                ['practician' => new PracticianResource($practician)]
            );
        }
        return $this->successResponse(
            __('Error updating password, old password incorrect'),
            ['practician' => new PracticianResource($practician)]
        );
    }

    public function create(RegisterRequest $request)
    {
        $user = Practician::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email??null,
            'password' => Hash::make($request->password??'12345678'),
            'gender' => $request->gender,
            'birthdate' => $request->birthdate,
            'phone_number' => str_replace(" ", "", $request->phone_number),
        ]);

        $user->assignRole($request->role??'practician');
        DB::table('establishment_practician')->insert([
            'establishment_id' => $request->establishment_id,
            'practician_id' => $user->id
        ]);

        Mail::send(new PracticianCreatedMail($user));
        Mail::send(new AdminPracticianCreatedMail($user));

        return $this->successResponse(
            __('Your user account has been successfully created. A verification email has been sent to you.'),
            []
        );
    }

    public function getByEstablishment(Request $request, $id) {
        $practicians = Establishment::where('id', $id)->first();

        return $this->successResponse(
            __('Practicians got successfully'),
            ['practicians' => new PracticianResourceCollection($practicians->employees()->filter($request)->paginate($request->perPage??10))]
        );
    }

    public function getDocsByEstablishment(Request $request, $id) {
        $practicians = Establishment::where('id', $id)->first();

        return $this->successResponse(
            __('Practicians got successfully'),
            ['practicians' => new PracticianResourceCollection($practicians->employees()->whereHas('roles',  function ($query) {
                $query->where('name', 'practician');
            })->filter($request)->paginate($request->perPage??10))]
        );
    }

    public function destroy($id)
    {
        $practician = Practician::findOrFail($id);

        $practician->delete();

        return $this->successResponse(__('Deleted practician successfully!'));
    }
}
