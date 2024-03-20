<?php

namespace Modules\Practician\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Modules\Core\Http\Controllers\Api\CoreController;
use Modules\Practician\Emails\PracticianValidated;
use Modules\Practician\Entities\Practician;
use Modules\Practician\Http\Requests\ValidatePracticianRequest;
use Modules\Practician\Transformers\PracticianResource;
use Modules\Practician\Transformers\PracticianResourceCollection;

class AdminController extends CoreController
{
    public function getAllPracticians(Request $request)
    {
        $practicians = Practician::with('roles')
//            ->where('is_active','=',true)
            ->filter($request)
            ->orderBy("created_at",'desc')
            ->paginate($request->get('per_page', 10));

        return $this->successResponse(__('Gets all practicians successfully'), [
            'practicians' => new PracticianResourceCollection($practicians)
        ]);
    }

    public function getAdminPracticians(Request $request)
    {
        $practicians = Practician::with('roles')
            ->where('is_active','=',true)
            ->whereHas('roles',  function ($query) {
                $query->where('name', 'practician');
            })
            ->filter($request)
            ->paginate($request->get('per_page', 10));

        return $this->successResponse(__('Gets all admin practicians successfully'), [
            'practicians' => new PracticianResourceCollection($practicians)
        ]);
    }

    public function getInactifAdminPracticians(ValidatePracticianRequest $request)
    {
        $practicians = Practician::with('roles')
            ->where('is_active', false)
            ->whereHas('roles',  function ($query) {
                $query->where('name', 'practician');
            })
            ->filter($request)
            ->paginate($request->get('per_page', 10));

        return $this->successResponse(__('Gets all inactif admin practicians successfully'), [
            'practicians' => new PracticianResourceCollection($practicians)
        ]);
    }

    public function show(Request $request, $id)
    {
        $practician = Practician::findOrFail($id);

        return $this->successResponse(__('Gets practician successfully'), [
            'practician' => new PracticianResource($practician)
        ]);
    }

    public function validatePractician(ValidatePracticianRequest $request, $id) {
        $practician = Practician::findOrFail($id);
        if($practician->is_valid === true) {
            return $this->json('The practician is already validated');
        }
        $password = Str::random(10);
        $practician->update([
            'is_valid' => true,
            'is_active' => true,
            'password' => Hash::make($password),
        ]);

        Mail::send(new PracticianValidated($practician, $password));
        return $this->successResponse(__('Activated practician successfully'), [
            'practician' => new PracticianResource($practician)
        ]);
    }

    public function activatePractician(ValidatePracticianRequest $request, $id) {
        $practician = Practician::findOrFail($id);

        $practician->update([
            'is_active' => !$practician->is_active
        ]);

        return $this->successResponse(__('Activated/Deactivated practician successfully'), [
            'practician' => new PracticianResource($practician)
        ]);
    }

}
