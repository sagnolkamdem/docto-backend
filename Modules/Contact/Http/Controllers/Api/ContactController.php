<?php

namespace Modules\Contact\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Contact\Entities\Contact;
use Modules\Contact\Transformers\ContactResource;
use Modules\Contact\Transformers\ContactResourceCollection;
use Modules\Core\Http\Controllers\Api\CoreController;

class ContactController extends CoreController
{
    public function getAll(Request $request)
    {
        $contacts = Contact::query()->filter($request)
            ->paginate($request->query('per_page', 10));

        return $this->successResponse('Got contacts successfully',[ "contacts" => new ContactResourceCollection($contacts)]);
    }

    public function show(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);

        return $this->successResponse(
            __('Get contact successfully'),
            ['contact' => new ContactResource($contact)]
        );
    }

    public function create(Request $request)
    {
        $contact = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'message' => $request->message,
        ];

        $contact = array_filter($contact);

        $contact = Contact::create($contact);
        return $this->successResponse(
            __('Your contact has been successfully created.'),
            ['contact' => new ContactResource($contact)]
        );
    }

    public function update(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);
        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'message' => $request->message,
            'status' => $request->status,
        ];

        $data = array_filter($data);

        $contact->update($data);

        return $this->successResponse(
            __('Update contact successfully'),
            ['contact' => new ContactResource($contact)]
        );
    }

    public function destroy(Request $request,$id)
    {
        $contact = Contact::findOrFail($id);

//        $this->authorize(Policy::DELETE, $contact);

        $contact->delete();

        return $this->successResponse(__('Deleted contact successfully!'));
    }
}
