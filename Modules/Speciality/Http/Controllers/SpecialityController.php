<?php

namespace Modules\Speciality\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Entities\City;
use Modules\Core\Http\Controllers\Api\CoreController;
use Modules\Core\Http\Requests\Api\ChangeCountryStatusRequest;
use Modules\Core\Transformers\CityResource;
use Modules\Practician\Http\Requests\ValidatePracticianRequest;
use Modules\Practician\Transformers\PracticianResourceCollection;
use Modules\Speciality\Entities\Speciality;
use Modules\Speciality\Transformers\SpecialityResource;
use Modules\Speciality\Transformers\SpecialityResourceCollection;

class SpecialityController extends CoreController
{
    public function index(Request $request)
    {
        $specialities = Speciality::filter($request)->paginate($request->query('per_page', 10));

        return $this->successResponse(
            __('Get specialities correctly.'),
            ['speciality' => new SpecialityResourceCollection($specialities)]
        );
    }

    public function store(ValidatePracticianRequest $request)
    {
        $speciality = [
            'name' => $request->name,
            'code' => $request->code,
            'status' => $request->status??false,
            'avatar' => $request->avatar,
        ];

        $speciality = Speciality::create($speciality);
        return $this->successResponse(
            __('Your speciality has been successfully created.'),
            ['speciality' => new SpecialityResource($speciality)]
        );
    }

    public function show($id)
    {
        $speciality = Speciality::findOrFail($id);

        return $this->successResponse(
            __('Get speciality successfully'),
            ['speciality' => new SpecialityResource($speciality)]
        );
    }

    public function available(Request $request, $id)
    {
        $date = $request->date ?? now()->toDateString();
        $practicians = Speciality::find($id)->practicians()->whereHas('timeSlots', function ($query) use ($date) {
            $query->where('payload->date', '=', $date)
                ->where('appointment_id', null);
            })->paginate($request->per_page??10);


        return $this->successResponse(
            __('Get practicians successfully'),
            ['practicians' => new PracticianResourceCollection($practicians)]
        );
    }

    public function update(ValidatePracticianRequest $request, $id)
    {
        $speciality = Speciality::findOrFail($id);
        $data = [
            'name' => $request->name,
            'code' => $request->code,
            'status' => $request->status,
            'avatar' => $request->avatar,
        ];
        $data = array_filter($data);
        $speciality->update($data);

        return $this->successResponse(
            __('Update speciality successfully'),
            ['speciality' => new SpecialityResource($speciality)]
        );
    }

    public function destroy($id)
    {
        $speciality = Speciality::findOrFail($id);

//        $this->authorize(Policy::DELETE, $speciality);

        $speciality->delete();

        return $this->successResponse(__('Deleted speciality successfully!'));
    }

    public function changeStatus(ChangeCountryStatusRequest $request, $id){
        $speciality = Speciality::findOrFail($id);
        $data = [
            'status' => $request->status,
        ];
        $speciality->update($data);

        return $this->successResponse(
            __('This speciality status has been successfully changed!'),
            [
                'speciality' => new SpecialityResource($speciality),
            ]
        );
    }
}
