<?php

namespace Modules\Core\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Core\Entities\City;
use Modules\Core\Entities\Commune;
use Modules\Core\Entities\Wilaya;
use Modules\Core\Http\Requests\Api\ChangeCountryStatusRequest;
use Modules\Core\Http\Requests\Api\GetAllCountriesRequest;
use Modules\Core\Transformers\CityResource;
use Modules\Core\Transformers\CityResourceCollection;
use Modules\Core\Transformers\WilayaResource;
use Modules\Practician\Http\Requests\ValidatePracticianRequest;

class CitiesController extends CoreController
{
    public function index(\Dingo\Api\Http\Request $request)
    {
        $cities = Commune::query()->filter($request)->orderBy('nom')->paginate($request->get('per_page', 1000));

        return $this->successResponse(
            __('Get cities successfully !'),
            ['cities' => new CityResourceCollection($cities)]
        );
    }

    public function location(\Dingo\Api\Http\Request $request)
    {
        $cities = Commune::query()->filter($request)->paginate($request->get('per_page', 1000));
        $wilaya = Wilaya::query()->paginate($request->get('per_page', 1000));

        return $this->successResponse(
            __('Get locations successfully !'),
            [
                'cities' => new CityResourceCollection($cities),
                'wilaya' => WilayaResource::collection($wilaya)
            ]
        );
    }

    public function getAllActiveCities(GetAllCountriesRequest $request)
    {
        $cities = City::query()->isActive()->filter($request)->paginate($request->get('per_page', 10));

        return $this->successResponse(
            __('Get cities successfully !'),
            ['cities' => new CityResourceCollection($cities)]
        );
    }

    public function store(ValidatePracticianRequest $request)
    {
        $wilaya_id=Wilaya::where("nom",$request->willaya_id)->first();
        $commune = [
            'nom' => $request->name,
            'wilaya_id' => $wilaya_id->id,
            'code_postal' => $request->code_postal,
        ];

        $commune = Commune::create($commune);
        return $this->successResponse(
            __('Your commune has been successfully created.'),
            ['commune' => new CityResource($commune)]
        );
    }

    public function show($id)
    {
        $city = Commune::query()->findOrFail($id);

        return $this->successResponse(
            __('Get city successfully !'),
            ["city" => new CityResource($city)]
        );
    }

    public function update(ValidatePracticianRequest $request, $id)
    {
        $commune = Commune::findOrFail($id);
        $data = [
            'name' => $request->name,
            'willaya_id' => $request->willaya_id,
            'code_postal' => $request->code_postal,
        ];
//        $data = array_filter($data);

        $commune->update($data);

        return $this->successResponse(
            __('This commune status has been successfully changed!'),
            [
                'commune' => new CityResource($commune),
            ]
        );
    }

    public function destroy($id)
    {
        $city = Commune::findOrFail($id);

//        $this->authorize(Policy::DELETE, $city);

        $city->delete();

        return $this->successResponse(__('Deleted city successfully!'));
    }

    public function changeStatus(ChangeCountryStatusRequest $request, $id){
        $city = City::findOrFail($id);
        $data = [
            'is_active' => $request->is_active,
        ];
//        $data = array_filter($data);

        $city->update($data);

        return $this->successResponse(
            __('This city status has been successfully changed!'),
            [
                'city' => new CityResource($city),
            ]
        );
    }
}
