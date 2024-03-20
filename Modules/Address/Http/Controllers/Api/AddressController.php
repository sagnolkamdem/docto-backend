<?php

namespace Modules\Address\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Address\Entities\Address;
use Modules\Address\Transformers\AddressResourceCollection;
use Modules\Core\Http\Controllers\Api\CoreController;
use Modules\Establishment\Entities\Establishment;

class AddressController extends CoreController
{
    public function getByEstablishment(Request $request, $id)
    {
        $establishment = Establishment::where('id',$id)->first();
        return $this->successResponse(
            __('Get addresses successfully'),
            ['addresses' => new AddressResourceCollection($establishment->addresss()->paginate($request->per_page ?? 10))]
        );
    }

    public function getByPractician(Request $request, $id)
    {
        $establishment = Establishment::where('id',$id)->first();
        return $this->successResponse(
            __('Get addresses successfully'),
            ['addresses' => new AddressResourceCollection($establishment->addresss()->paginate($request->per_page ?? 10))]
        );
    }

    public function createForEstablishment(Request $request)
    {
        $address = [
            'establishment_id' => $request->establishment_id,
            'commune_id' => $request->commune_id,
            'address_lines' => $request->address_lines,
            'description' => $request->description??'',
        ];

        $address = Address::create($address);
        return $this->successResponse(
            __('Your address has been successfully created.'),
            ['address' => $address]
        );
    }

    public function createForPractician(Request $request)
    {
        $address = [
            'practician_id' => $request->practician_id,
            'commune_id' => $request->commune_id,
            'address_lines' => $request->address_lines,
            'description' => $request->description,
        ];

        $address = Address::create($address);
        return $this->successResponse(
            __('Your address has been successfully created.'),
            ['address' => $address]
        );
    }

    public function getAll(Request $request)
    {
        $addresses = Address::paginate($request->query('per_page', 10));

        return $this->json($addresses);
    }

    public function show(Request $request, $id)
    {
        $address = Address::findOrFail($id);

        return $this->successResponse(
            __('Get address successfully'),
            ['address' => $address]
        );
    }

    public function update(Request $request, $id)
    {
        $address = Address::findOrFail($id);
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'commune_id' => $request->commune_id,
            'address_lines' => $request->address_lines,
            'status' => $request->status??$address->status
        ];

        $address->update($data);

        return $this->successResponse(
            __('Update address successfully'),
            ['address' => $address]
        );
    }

    public function destroy(Request $request,$id)
    {
        $address = Address::findOrFail($id);

//        $this->authorize(Policy::DELETE, $address);

        $address->delete();

        return $this->successResponse(__('Deleted address successfully!'));
    }
}
