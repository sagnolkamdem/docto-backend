<?php

namespace Modules\Motif\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Http\Controllers\Api\CoreController;
use Modules\Motif\Entities\Motif;
use Modules\Motif\Transformers\MotifResource;
use Modules\Motif\Transformers\MotifResourceCollection;

class MotifController extends CoreController
{
    public function getAll(Request $request)
    {
        $motifs = Motif::query()->where('practician_id', $request->user()?->id)
            ->paginate($request->query('per_page', 10));

        return $this->successResponse('Got motifs successfully',[ "motifs" => new MotifResourceCollection($motifs)]);
    }

    public function show(Request $request, $id)
    {
        $motif = Motif::findOrFail($id);

        return $this->successResponse(
            __('Get motif successfully'),
            ['motif' => new MotifResource($motif)]
        );
    }

    public function create(Request $request)
    {
        $motif = [
            'name' => $request->name,
            'description' => $request->description,
            'enabled' => $request->enabled,
            'practician_id' => $request->practician_id ?? Auth::guard('pro')->user()?->id,
        ];

        $motif = array_filter($motif);

        $motif = Motif::create($motif);
        return $this->successResponse(
            __('Your motif has been successfully created.'),
            ['motif' => $motif]
        );
    }

    public function update(Request $request, $id)
    {
        $motif = Motif::findOrFail($id);
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'enabled' => $request->enabled??$motif->enabled,
            'practician_id' => $request->practician_id ?? Auth::guard('pro')->user()?->id,
        ];

        $motif->update($data);

        return $this->successResponse(
            __('Update motif successfully'),
            ['motif' => new MotifResource($motif)]
        );
    }

    public function destroy(Request $request,$id)
    {
        $motif = Motif::findOrFail($id);

//        $this->authorize(Policy::DELETE, $motif);

        $motif->delete();

        return $this->successResponse(__('Deleted motif successfully!'));
    }
}
