<?php

namespace Modules\User\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Authorization\Entities\Role;
use Modules\Core\Http\Controllers\Api\CoreController;
use Modules\Practician\Http\Requests\ValidatePracticianRequest;
use Modules\Speciality\Entities\Speciality;
use Modules\Speciality\Transformers\SpecialityResource;
use Modules\User\Http\Requests\Api\PatientsRequest;
use Modules\User\Transformers\RoleResource;
use Modules\User\Transformers\RoleResourceCollection;

class RoleController extends CoreController
{
    public function index(PatientsRequest $request)
    {
        $roles = Role::query()->filter($request)->paginate($request->get('per_page', 10));

        return $this->successResponse(__('Gets all roles successfully'), [
            'roles' => new RoleResourceCollection($roles)
        ]);
    }

    public function show(PatientsRequest $request, $id)
    {
        $role = Role::findOrFail($id);

        return $this->successResponse(__('Gets role successfully'), [
            'role' => new RoleResource($role)
        ]);
    }

    public function update(ValidatePracticianRequest $request, $id)
    {
        $role = Role::findOrFail($id);
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status??$role->status,
        ];
        $data = array_filter($data);
        $role->update($data);

        return $this->successResponse(
            __('Update role successfully'),
            ['role' => new RoleResource($role)]
        );
    }

    public function destroy($id)
    {
        //
    }
}
