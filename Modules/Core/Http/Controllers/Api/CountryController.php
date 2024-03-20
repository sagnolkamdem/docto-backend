<?php

namespace Modules\Core\Http\Controllers\Api;

use Dingo\Api\Http\Request;
use Modules\Core\Entities\Country;
use Modules\Core\Http\Requests\Api\ChangeCountryStatusRequest;
use Modules\Core\Http\Requests\Api\GetAllCountriesRequest;
use Modules\Core\Transformers\CountryResource;
use Modules\Core\Transformers\CountryResourceCollection;

/**
 * @group  Countries management
 *
 * APIs for managing countries
 *
 * @Resource("Country", uri="/countries")
 */
class CountryController extends CoreController
{

    public function store(Request $request)
    {
        //
    }

    /**
     * Get countries.
     *
     * Get all countries
     *
     * @Get("/")
     * @Versions({"v1"})
     */
    public function getAllCountries(GetAllCountriesRequest $request)
    {
        $countries = Country::query()->filter($request)->paginate($request->get('per_page', 1000));

        return $this->successResponse(
            __('Get countries successfully !'),
            ['countries' => new CountryResourceCollection($countries)]
        );
    }

    public function getAllEnabledCountries(GetAllCountriesRequest $request)
    {
        $countries = Country::query()->isEnabled()->filter($request)->paginate($request->get('per_page', 10));

        return $this->successResponse(
            __('Get countries successfully !'),
            ['countries' => new CountryResourceCollection($countries)]
        );
    }

    public function getAllActiveCountries(GetAllCountriesRequest $request)
    {
        $countries = Country::query()->isActive()->filter($request)->paginate($request->get('per_page', 10));

        return $this->successResponse(
            __('Get countries successfully !'),
            ['countries' => new CountryResourceCollection($countries)]
        );
    }

    /**
     * Get country by id.
     *
     * Get country by id
     *
     * @Get("/{id}")
     * @Versions({"v1"})
     */
    public function getCountry(int $countryId)
    {
        $country = Country::findOrFail($countryId);

        return $this->successResponse(
            __('Get country successfully !'),
            [new CountryResource($country)]
        );
    }

    public function changeStatus(ChangeCountryStatusRequest $request, $id){
        $country = Country::findOrFail($id);
        $data = [
            'is_active' => $request->is_active,
            'is_enabled' => $request->is_enabled,
        ];
//        $data = array_filter($data);

        $country->update($data);

        return $this->successResponse(
            __('This country status has been successfully changed!'),
            [
                'country' => new CountryResource($country),
            ]
        );
    }
}
