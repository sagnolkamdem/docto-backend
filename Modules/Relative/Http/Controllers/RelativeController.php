<?php

namespace Modules\Relative\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Http\Controllers\Api\CoreController;
use Modules\LogActivity\Helpers\LogActivity;
use Modules\Relative\Entities\Relative;
use Modules\Relative\Http\Requests\Api\CreateRelativeRequest;
use Modules\Relative\Http\Requests\Api\GetRelativeRequest;
use Modules\Relative\Http\Requests\Api\UpdateRelativeRequest;
use Modules\Relative\Transformers\RelativeResource;
use Modules\Relative\Transformers\RelativeResourceCollection;
use Modules\User\Entities\User;

class RelativeController extends CoreController
{

    public function getAll(GetRelativeRequest $request)
    {
        $relatives = Relative::paginate($request->query('per_page', 10));

        return $this->json(new RelativeResourceCollection($relatives));
    }

    public function getByUserId(GetRelativeRequest $request, $id)
    {
        $relatives = Relative::query()
            ->whereHas('patient',  function ($query) use($id) {
                $query->where('parent_id', $id);
            })
            ->paginate($request->query('per_page', 10));

        return $this->successResponse(
            __('Your relatives has been successfully gotten.'),
            ['relatives' => new RelativeResourceCollection($relatives)]
        );
    }

    public function show(GetRelativeRequest $request, $id)
    {
        $relative = Relative::findOrFail($id);

        return $this->successResponse(
            __('Get relative successfully'),
            ['relative' => new RelativeResource($relative)]
        );
    }

    public function create(CreateRelativeRequest $request)
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'birthdate' => $request->birthdate,
            'phone_number' => $request->phone_number,
            'can_login' => false,
            'parent_id' => $request->patient_id,
        ]);

        $user->assignRole('patient');

        $relative = [
            'patient_id' => $user->id,
            'type' => $request->type,
            'gender' => $request->gender,
            'birthdate' => $request->birthdate,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'place_of_birth' => $request->place_of_birth,
            'address' => $request->address,
            'email' => $request->email,
            'height' => $request->height,
            'weight' => $request->weight,
            'is_patient' => $request->is_patient,
        ];

        $relative = array_filter($relative);

        $relative = Relative::create($relative);
        $time = now()->toDateTimeString();
        LogActivity::addToLog("Le proche ($relative->first_name) à été ajouté a votre compte le: $time");
        return $this->successResponse(
            __('Your relative has been successfully created.'),
            ['relative' => new RelativeResource($relative)]
        );
    }

    public function update(UpdateRelativeRequest $request, $id)
    {
        $relative = Relative::findOrFail($id);
        $data = [
            'type' => $request->type,
            'gender' => $request->gender,
            'birthdate' => $request->birthdate,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'place_of_birth' => $request->place_of_birth,
            'email' => $request->email,
            'address' => $request->address,
            'height' => $request->height,
            'weight' => $request->weight,
            'is_patient' => $request->is_patient,
        ];
        $data = array_filter($data);
        $relative->update($data);
        $time = now()->toDateTimeString();
        LogActivity::addToLog("Le proche ($relative->first_name) à été mis a jour le: $time");

        return $this->successResponse(
            __('Update relative successfully'),
            ['relative' => new RelativeResource($relative)]
        );
    }

    public function destroy(GetRelativeRequest $request,$id)
    {
        $relative = Relative::findOrFail($id);
        $name = $relative->first_name;
//        $this->authorize(Policy::DELETE, $relative);

        $relative->delete();
        $time = now()->toDateTimeString();
        LogActivity::addToLog("Le proche ($name) à été supprimé de votre compte le: $time");

        return $this->successResponse(__('Deleted relative successfully!'));
    }
}
