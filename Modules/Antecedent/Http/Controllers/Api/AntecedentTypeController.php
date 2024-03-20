<?php

namespace Modules\Antecedent\Http\Controllers\Api;

use Modules\Antecedent\Entities\AntecedentType;
use Modules\Antecedent\Http\Requests\Api\CreateAntecedentTypeRequest;
use Modules\Antecedent\Http\Requests\Api\GetAntecedentTypeRequest;
use Modules\Antecedent\Http\Requests\Api\UpdateAntecedentTypeRequest;
use Modules\Antecedent\Transformers\AntecedentTypeResource;
use Modules\Core\Http\Controllers\Api\CoreController;

class AntecedentTypeController extends CoreController
{
    public function getAll(GetAntecedentTypeRequest $request)
    {
        $antecedentTypes = AntecedentType::paginate($request->query('per_page', 10));

        return $this->json($antecedentTypes);
    }

    public function show(GetAntecedentTypeRequest $request, $id)
    {
        $antecedentType = AntecedentType::findOrFail($id);

        return $this->successResponse(
            __('Get antecedentType successfully'),
            ['antecedentType' => new AntecedentTypeResource($antecedentType)]
        );
    }

    public function create(CreateAntecedentTypeRequest $request)
    {
        $antecedentType = [
            'name' => $request->name,
            'description' => $request->description,
            'enabled' => $request->enabled,
        ];

        $antecedentType = array_filter($antecedentType);

        $antecedentType = AntecedentType::create($antecedentType);
        return $this->successResponse(
            __('Your antecedentType has been successfully created.'),
            ['antecedentType' => new AntecedentTypeResource($antecedentType)]
        );
    }

    public function update(UpdateAntecedentTypeRequest $request, $id)
    {
        $antecedentType = AntecedentType::findOrFail($id);
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'enabled' => $request->enabled??$antecedentType->enabled,
        ];

        $antecedentType->update($data);

        return $this->successResponse(
            __('Update antecedentType successfully'),
            ['antecedentType' => new AntecedentTypeResource($antecedentType)]
        );
    }

    public function destroy(GetAntecedentTypeRequest $request,$id)
    {
        $antecedentType = AntecedentType::findOrFail($id);

//        $this->authorize(Policy::DELETE, $antecedentType);

        $antecedentType->delete();

        return $this->successResponse(__('Deleted antecedentType successfully!'));
    }
}
