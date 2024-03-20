<?php

namespace Modules\Signature\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Http\Controllers\Api\CoreController;
use Modules\Signature\Entities\Signature;
use Modules\Signature\Http\Requests\Api\CreateSignatureRequest;
use Modules\Signature\Transformers\SignatureResource;

class SignatureController extends CoreController
{
    public function index(Request $request)
    {
        $signature = Signature::query()
            ->where('practician_id', '=', $request->practician_id ?? auth()->user('pro')?->id)
            ->first();

        return $signature ? $this->successResponse(
            __('Get signature successfully'),
            ['signature' => new SignatureResource($signature)]) :
            $this->successResponse(
            __('Get signature successfully'),
            ['signature' => $signature]
        );
    }

    public function store(CreateSignatureRequest $request)
    {
        $path = '';
        $name = "";
        if ($request->hasFile('file')) {

            $file = $request->file('file');

            $name =  "signature_" . now()->timestamp . "." . $file->getClientOriginalExtension();

            $path = config('app.url') . '/storage/' . $file->storeAs('signature', $name, 'public');
        }
        $data = array(
            'path' => $path,
            'filename' => $name,
            'practician_id' => $request->practician_id ?? auth('sanctum')->user()->id
        );

        $signature = Signature::create($data);

        return $this->successResponse(
            __('Created signature successfully'),
            ['signature' => new SignatureResource($signature)]
        );
    }

    public function show($id)
    {
        $signature = Signature::findOrFail($id);

        return $this->successResponse(
            __('Get signature successfully'),
            ['signature' => new SignatureResource($signature)]
        );
    }

    public function update(Request $request, $id)
    {
        $signature = Signature::findOrFail($id);

        $path = $signature->path;
        $name = $signature->filename;
        if ($request->hasFile('file')) {

            $file = $request->file('file');

            $name =  "signature_" . now()->timestamp . "." . $file->getClientOriginalExtension();

            $path = config('app.url') . '/storage/' . $file->storeAs('signature', $name, 'public');
        }
        $data = array(
            'path' => $path,
            'filename' => $name,
        );

        $data = array_filter($data);
        $signature->update($data);

        return $this->successResponse(
            __('Update signature successfully'),
            ['signature' => new SignatureResource($signature)]
        );
    }

    public function destroy($id)
    {
        $signature = Signature::findOrFail($id);
        if($signature->practician_id == auth()
                ->guard('sanctum')
                ->user()->id) {
            $signature->delete();

            return $this->successResponse(__('Deleted document successfully!'));
        }
        return $this->errorResponse(__('You cannot delete a document you did not create!'), [], 500);
    }
}
