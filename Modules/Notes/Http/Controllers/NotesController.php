<?php

namespace Modules\Notes\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Modules\Core\Http\Controllers\Api\CoreController;
use Modules\Establishment\Entities\Establishment;
use Modules\Notes\Entities\Note;
use Modules\Notes\Http\Requests\CreateNoteRequest;
use Modules\Notes\Http\Requests\GetNoteRequest;
use Modules\Notes\Http\Requests\UpdateNoteRequest;
use Modules\Notes\Transformers\NotesResource;
use Modules\Notes\Transformers\NotesResourceCollection;

class NotesController extends CoreController
{
    public function getAll(GetNoteRequest $request)
    {
        $notes = Note::query()
            ->where('created_by_practician', true)
            ->where('created_by', auth('sanctum')->user()->id)
            ->filter($request)->paginate($request->query('per_page', 10));

        return $this->json(['notes' =>new NotesResourceCollection($notes)]);
    }

    public function getByUserId(GetNoteRequest $request, $id)
    {
        $notes = Note::with(['author','patient'])->filter($request)
            ->where('patient_id', $id)
            ->orWhereHas('patient', function ($query) use ($id) {
                $query->where('parent_id', $id);
            })
            ->paginate($request->query('per_page', 10));

        return $this->successResponse(
            __('Your notes has been successfully gotten.'),
            ['notes' => new NotesResourceCollection($notes)]
        );
    }

    public function getByEstablishmentId(GetNoteRequest $request, $id)
    {
        $establishment = Establishment::where('id', $id)->first();

        $practos = $establishment->employees()->pluck('id')->toArray();

        $notes = Note::query()->filter($request)
            ->where('created_by_practician', true)
            ->whereIn('created_by', $practos)
            ->paginate($request->query('per_page', 10));

        return $this->successResponse(
            __('Your notes has been successfully gotten.'),
            ['notes' => new NotesResourceCollection($notes)]
        );
    }

    public function show(GetNoteRequest $request, $id)
    {
        $note = Note::findOrFail($id);

        return $this->successResponse(
            __('Get note successfully'),
            ['note' => new NotesResource($note)]
        );
    }

    public function update(UpdateNoteRequest $request, $id)
    {
        $note = Note::findOrFail($id);
        $createdBy = $request->created_by_practician ?? $note->created_by_prcatician;
        $data = [
            'title' => $request->title,
            'content' => $request->get('content'),
            'patient_id' => $request->patient_id,
            'created_by_practician' => $createdBy,
            'created_by' => $createdBy === true ? Auth::guard('pro')->user()?->id : Auth::user()->id,
        ];

        $data = array_filter($data);
        $note->update($data);
        return $this->successResponse(
            __('Update note successfully'),
            ['note' => new NotesResource($note)]
        );
    }

    public function store(CreateNoteRequest $request)
    {
        $createdBy = $request->created_by_practician ?? false;
            $data = [
            'title' => $request->title,
            'content' => $request->get('content'),
            'patient_id' => $request->patient_id,
            'created_by_practician' => $createdBy,
            'created_by' => $createdBy === true ? Auth::guard('pro')->user()->id : Auth::user()->id,
        ];
        $note = Note::create($data);

        return $this->successResponse(
            __('Created note successfully'),
            ['note' => new NotesResource($note)]
        );
    }

    public function delete(GetNoteRequest $request,$id)
    {
        $note = Note::findOrFail($id);

//        $this->authorize(Policy::DELETE, $note);

        $note->delete();

        return $this->successResponse(__('Deleted note successfully!'));
    }
}
