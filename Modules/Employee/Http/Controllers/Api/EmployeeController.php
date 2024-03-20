<?php

namespace Modules\Employee\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\Core\Http\Controllers\Api\CoreController;
use Modules\Employee\Http\Requests\AdminRequest;
use Modules\Employee\Http\Requests\GetEmployeesRequest;
use Modules\Employee\Transformers\EmployeeResource;
use Modules\Employee\Transformers\EmployeeResourceCollection;
use Modules\Practician\Http\Requests\ValidatePracticianRequest;
use Modules\Employee\Emails\EmployeeCreatedMail;
use Modules\User\Entities\User;

class EmployeeController extends CoreController
{
    public function index(GetEmployeesRequest $request)
    {
        $employees = User::query()->whereHas('roles',function ($query) {
            $query->whereIn('name', ['manager','root', 'secretary_employee', 'admin']);
        })->filter($request)->orderBy("created_at",'desc')->paginate($request->query('per_page', 10));

        return $this->json(['employees' =>new EmployeeResourceCollection($employees)]);
    }

    public function store(ValidatePracticianRequest $request)
    {
        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'status' => $request->status,
            'created_by' => auth('sanctum')->user()->id ?? null
        ];

        $employee = User::create($data);

        $employee->assignRole($request->role);

        Mail::send(new EmployeeCreatedMail($employee));

        return $this->successResponse(
            __('Created employee successfully'),
            ['employee' => new EmployeeResource($employee)]
        );
    }

    public function show($id)
    {
        $employee = User::findOrFail($id);

        return $this->successResponse(
            __('Get employee successfully'),
            ['employee' => new EmployeeResource($employee)]
        );
    }

    public function update(ValidatePracticianRequest $request, $id)
    {
        $employee = User::findOrFail($id);
        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'status' => $request->status??$employee->status,
        ];
        $data = array_filter($data);
        $employee->update($data);

        return $this->successResponse(
            __('Update employee successfully'),
            ['employee' => new EmployeeResource($employee)]
        );
    }

    public function destroy(AdminRequest $request, $id)
    {
        $employee = User::findOrFail($id);

//        $this->authorize(Policy::DELETE, $employee);

        $employee->delete();

        return $this->successResponse(__('Deleted employee successfully!'));
    }
}
