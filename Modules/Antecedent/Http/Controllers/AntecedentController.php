<?php

namespace Modules\Antecedent\Http\Controllers;

use Modules\Antecedent\Entities\Antecedent;
use Modules\Antecedent\Http\Requests\Api\GetAntecedentRequest;
use Modules\Antecedent\Http\Requests\Api\UpdateAntecedentRequest;
use Modules\Antecedent\Transformers\AntecedentResource;
use Modules\Antecedent\Transformers\AntecedentResourceCollection;
use Modules\Core\Http\Controllers\Api\CoreController;

class AntecedentController extends CoreController
{
    public function getAll(GetAntecedentRequest $request)
    {
        $antecedents = Antecedent::paginate($request->query('per_page', 10));

        return $this->json(new AntecedentResourceCollection($antecedents));
    }

    public function getByUserId(GetAntecedentRequest $request, $id)
    {
        $antecedents = Antecedent::where('user_id', $id)->paginate($request->query('per_page', 10));

        return $this->successResponse(
            __('Your antecedents has been successfully gotten.'),
            ['antecedents' => new AntecedentResourceCollection($antecedents)]
        );
    }

    public function show(GetAntecedentRequest $request, $id)
    {
        $antecedent = Antecedent::findOrFail($id);

        return $this->successResponse(
            __('Get antecedent successfully'),
            ['antecedent' => new AntecedentResource($antecedent)]
        );
    }

    public function update(UpdateAntecedentRequest $request, $id)
    {
        $antecedent = Antecedent::findOrFail($id);
        $data = [
            'type_id' => $request->type_id,
            'user_id' => $request->user_id,
            'description' => $request->description,
        ];
        $data = array_filter($data);
        $antecedent->update($data);

        return $this->successResponse(
            __('Update antecedent successfully'),
            ['antecedent' => new AntecedentResource($antecedent)]
        );
    }

    public function store(UpdateAntecedentRequest $request, $id)
    {
        $data = [
            'type_id' => $request->type_id,
            'user_id' => $request->user()->id,
            'description' => $request->description,
        ];
        $antecedent = Antecedent::create($data);

        return $this->successResponse(
            __('Created antecedent successfully'),
            ['antecedent' => new AntecedentResource($antecedent)]
        );
    }

    public function destroy(GetAntecedentRequest $request,$id)
    {
        $antecedent = Antecedent::findOrFail($id);

//        $this->authorize(Policy::DELETE, $antecedent);

        $antecedent->delete();

        return $this->successResponse(__('Deleted antecedent successfully!'));
    }
}
