<?php

namespace Modules\Core\Http\Controllers\Api;

use Dingo\Api\Http\Request;
use Dingo\Api\Http\Response;
use Modules\Core\Entities\Currency;
use Modules\Core\Http\Requests\Api\ChangeCountryStatusRequest;
use Modules\Core\Http\Requests\Api\GetAllCountriesRequest;
use Modules\Core\Transformers\CurrencyResource;
use Modules\Core\Transformers\CurrencyResourceCollection;

class CurrencyController extends CoreController
{
    public function index(Request $request)
    {
        $currencies = Currency::query()->filter($request)->paginate($request->get('per_page', 10));

        return $this->successResponse(
            __('Get currencies successfully !'),
            ['currencies' => new CurrencyResourceCollection($currencies)]
        );
    }

    public function getAllActiveCurrencies(GetAllCountriesRequest $request)
    {
        $currencies = Currency::query()->isActive()->filter($request)->paginate($request->get('per_page', 10));

        return $this->successResponse(
            __('Get currencies successfully !'),
            ['currencies' => new CurrencyResourceCollection($currencies)]
        );
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $currency = Currency::query()->findOrFail($id);

        return $this->successResponse(
            __('Get currency successfully !'),
            ["currency" => new CurrencyResource($currency)]
        );
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function changeStatus(ChangeCountryStatusRequest $request, $id){
        $currency = Currency::findOrFail($id);
        $data = [
            'is_active' => $request->is_active,
        ];
//        $data = array_filter($data);

        $currency->update($data);

        return $this->successResponse(
            __('This currency status has been successfully changed!'),
            [
                'currency' => new CurrencyResource($currency),
            ]
        );
    }
}
