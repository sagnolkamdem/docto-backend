<?php

namespace Modules\Establishment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Core\Http\Controllers\Api\CoreController;
use Modules\Core\Http\Requests\Api\ChangeCountryStatusRequest;
use Modules\Establishment\Entities\Establishment;
use Modules\Establishment\Enums\EstablishmentType;
use Modules\Establishment\Transformers\EstablishmentResource;
use Modules\Establishment\Transformers\EstablishmentResourceCollection;
use Modules\Practician\Entities\Practician;
use Modules\Practician\Http\Requests\ValidatePracticianRequest;
use Modules\Speciality\Entities\Speciality;
use Modules\User\Http\Requests\Api\FindUserRequest;

class EstablishmentController extends CoreController
{
    public function index(FindUserRequest $request)
    {
        $establishements = Establishment::filter($request)
            ->orderBy("created_at",'desc')
            ->paginate($request->query('per_page', 10));

        return $this->successResponse(
            __('Got establishments successfully.'),
            ['establishment' => new EstablishmentResourceCollection($establishements)]
        );
    }

    public function store(ValidatePracticianRequest $request)
    {
        $establishment = [
            'name' => $request->name,
            'type' => $request->type,
            'city' => $request->city,
            'admin_practician' => $request->admin_practician,
            'address' => $request->address,
            'postal_code' => $request->postal_code,
            'status' => $request->status??false,
            'description' => $request->description,
            'emergency' => $request->emergency,
            'head_quarter' => $request->head_quarter,
            'time_slots' => $request->time_slots,
        ];

        $establishment = Establishment::create($establishment);
        DB::table('establishment_practician')->insert([
            'establishment_id' => $establishment->id,
            'practician_id' => $establishment->admin_practician
        ]);
        return $this->successResponse(
            __('Your establishment has been successfully created.'),
            ['establishment' => new EstablishmentResource($establishment)]
        );
    }

    public function show($id)
    {
        $establishment = Establishment::findOrFail($id);

        return $this->successResponse(
            __('Get establishment successfully'),
            ['establishment' => new EstablishmentResource($establishment)]
        );
    }

    public function update(Request $request, $id)
    {
        $establishment = Establishment::findOrFail($id);
        $data = [
            'name' => $request->name,
            'type' => $request->type,
            'city' => $request->city,
            'admin_practician' => $request->admin_practician,
            'address' => $request->address,
            'postal_code' => $request->postal_code,
            'status' => $request->status,
            'description' => $request->description,
            'emergency' => $request->emergency,
            'head_quarter' => $request->head_quarter,
            'time_slots' => $request->time_slots,
        ];
        $data = array_filter($data);
        $establishment->update($data);
        if ($data['admin_practician'] !== null) {
            DB::table('establishment_practician')->where('establishment_id', $establishment->id)->delete();
            DB::table('establishment_practician')->insert([
                'establishment_id' => $establishment->id,
                'practician_id' => $establishment->admin_practician
            ]);
        }

        return $this->successResponse(
            __('Update establishment successfully'),
            ['establishment' => new EstablishmentResource($establishment)]
        );
    }

    public function destroy($id)
    {
        $establishment = Establishment::findOrFail($id);

//        $this->authorize(Policy::DELETE, $establishment);

        $establishment->delete();

        return $this->successResponse(__('Deleted establishment successfully!'));
    }

    public function changeStatus(ChangeCountryStatusRequest $request, $id){
        $establishment = Establishment::findOrFail($id);
        $data = [
            'status' => $request->status,
        ];
        $establishment->update($data);

        return $this->successResponse(
            __('This establishment status has been successfully changed!'),
            [
                'establishment' => new EstablishmentResource($establishment),
            ]
        );
    }

    public function types()
    {
        return $this->successResponse(__('Get document types successfully!'), [
            'types' => EstablishmentType::getValues(),
        ]);
    }
}
