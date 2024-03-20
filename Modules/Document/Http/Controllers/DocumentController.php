<?php

namespace Modules\Document\Http\Controllers;

//use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Modules\Appointment\Entities\Appointment;
use Modules\Core\Http\Controllers\Api\CoreController;
use Modules\Document\Emails\NewDocumentMail;
use Modules\Document\Entities\Document;
use Modules\Document\Entities\DocumentFile;
use Modules\Document\Enums\DocumentType;
use Modules\Document\Http\Requests\CreateDocumentRequest;
use Modules\Document\Http\Requests\GetDocumentRequest;
use Modules\Document\Http\Requests\UpdateDocumentRequest;
use Modules\Document\Transformers\DocumentResource;
use Modules\Document\Transformers\DocumentResourceCollection;
use Modules\LogActivity\Helpers\LogActivity;
use Modules\User\Entities\User;

class DocumentController extends CoreController
{
    public function getAll(GetDocumentRequest $request)
    {
        $documents = Document::query()->filter($request)
            ->where('created_by_practician', true)
            ->where('created_by', auth('sanctum')->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate($request->query('per_page', 10));

        return $this->json(['documents' =>new DocumentResourceCollection($documents)]);
    }

    public function getByUserId(GetDocumentRequest $request, $id)
    {
        $documents = Document::with(['author','patient'])->filter($request)
            ->where('patient_id', $id)
            ->orWhereHas('patient', function ($query) use ($id) {
                $query->where('parent_id', $id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($request->query('per_page', 10));

        return $this->successResponse(
            __('Your documents has been successfully gotten.'),
            ['documents' => new DocumentResourceCollection($documents)]
        );
    }

    public function show(GetDocumentRequest $request, $id)
    {
        $document = Document::with(['author','patient'])->findOrFail($id);

        return $this->successResponse(
            __('Get document successfully'),
            ['document' => new DocumentResource($document)]
        );
    }

    public function update(UpdateDocumentRequest $request, $id)
    {
        $document = Document::findOrFail($id);
        $createdBy = $request->created_by_practician ?? $document->created_by_prcatician;
        $path = $document->path;
        $type = $request->type ?? $document->type;
        $patient = User::findOrFail($request->patient_id??$document->patient_id);
        $name = $document->filename;
        list($name, $data) = $this->addFile($request, $type, $patient, $name, $path, $createdBy);
        if ($request->input('files')){
            $files = $request->file('files');
            foreach ($files as $file) {
                $name = $type . "_" . $patient->first_name . "_" . now()->timestamp . "_" . $file->getFilename() . "." . $file->getClientOriginalExtension();
                $path = config('app.url') . '/storage/' . $file->storeAs('document-files', $name, 'public');
                $documentFile = new DocumentFile;
                $documentFile->document_id = $document->id;
                $documentFile->filename = $name;
                $documentFile->path = $path;
                $documentFile->save();
            }
        }
        $data = array_filter($data);
        $document->update($data);

        return $this->successResponse(
            __('Update document successfully'),
            ['document' => new DocumentResource($document)]
        );
    }

    public function store(CreateDocumentRequest $request)
    {
        $path = '';
        $type = $request->type;
        $patient = User::findOrFail($request->patient_id);
        $name = "";
        $createdBy = $request->created_by_practician ?? false;
        list($name, $data) = $this->addFile($request, $type, $patient, $name, $path, $createdBy);
        $document = Document::create($data);
        if ($request->file('files')){
            $files = $request->file('files');
            foreach ($files as $file) {
                $name = $type . "_" . $patient->first_name . "_" . now()->timestamp . "_" . $file->getFilename() . "." . $file->getClientOriginalExtension();
                $path = config('app.url') . '/storage/' . $file->storeAs('document-files', $name, 'public');
                $documentFile = new DocumentFile;
                $documentFile->document_id = $document->id;
                $documentFile->filename = $name;
                $documentFile->path = $path;
                $documentFile->save();
            }
        }
        $time = now()->toDateTimeString();
        LogActivity::addToLog("Un document ($name) à été ajouté a votre compte le: $time");

        if ($createdBy && ($patient->email != null || $patient->parent->email!= null)){
            if ($patient->parent != null) {
                Mail::send(new NewDocumentMail($patient->parent));
            }
            if ($patient->email!= null) {
                Mail::send(new NewDocumentMail($patient));
            }
        }
        return $this->successResponse(
            __('Created document successfully'),
            ['document' => new DocumentResource($document)]
        );
    }

    public function destroy(GetDocumentRequest $request,$id)
    {
        $document = Document::findOrFail($id);
        $name = $document->filename;
        if($document->created_by == auth()
                ->guard('sanctum')
                ->user()->id) {
            $document->delete();

            $time = now()->toDateTimeString();
            LogActivity::addToLog("Le document $name à été supprimé de votre compte le: $time");
            return $this->successResponse(__('Deleted document successfully!'));
        }
        return $this->errorResponse(__('You cannot delete a document you did not create!'), [], 500);
    }

    public function types()
    {
        return $this->successResponse(__('Get document types successfully!'), [
            'types' => DocumentType::getValues(),
        ]);
    }

    /**
     * @param CreateDocumentRequest $request
     * @param mixed $type
     * @param $patient
     * @param string $name
     * @param string $path
     * @return array
     */
    public function addFile(CreateDocumentRequest $request, mixed $type, $patient, string $name, string $path, bool $createdBy): array
    {
        if ($request->hasFile('file')) {

            $file = $request->file('file');

            $name = $type . "_" . $patient->first_name . "_" . now()->timestamp . "." . $file->getClientOriginalExtension();

            $path = config('app.url') . '/storage/' . $file->storeAs('documents', $name, 'public');
        } else {
            if ($request->metadata) {
                $metadata = $request->metadata;
                $name = $type . "_" . $patient->first_name . "_" . now()->timestamp . ".pdf";
                $appointment = Appointment::where("id",$request->appointment_id)->first();
                switch ($request->type) {
                    case DocumentType::ORDONANCES : {
                        $data = [
                            "title" => "CABINET MEDICAL",
                            "appointment" => $appointment,
                            "establishment" => $appointment->establishment,
                            "practician" => $appointment->practician,
                            "patient" => $appointment->patient,
                            "metadata" => json_decode(json_encode($metadata['data']))
                        ];
                        $pdf = PDF::loadView('document::ordonnance.ordonance', $data)
                            ->setPaper('a4');
                        $path = config('app.url') . '/storage/documents/'.$name;
                        $pdf->save(public_path("storage/documents/$name"));
                    }
                    break;
                    case DocumentType::VACCINE: {
                        $data = [
                            "title" => "CABINET MEDICAL",
                            "appointment" => $appointment,
                            "establishment" => $appointment->establishment,
                            "practician" => $appointment->practician,
                            "patient" => $appointment->patient,
                            "metadata" => json_decode(json_encode($metadata['data']))
                        ];
                        $pdf = PDF::loadView('document::vaccin.vaccin', $data)
                            ->setPaper('a4');
                        $path = config('app.url') . '/storage/documents/'.$name;
                        $pdf->save(public_path("storage/documents/$name"));
                    }
                        break;
                    case DocumentType::CERTIFICATE: {
                        $data = [
                            "appointment" => $appointment,
                            "establishment" => $appointment->establishment,
                            "practician" => $appointment->practician,
                            "patient" => $appointment->patient,
                            "metadata" => json_decode(json_encode($metadata))
                        ];
                        $pdf = PDF::loadView('document::certificat.certificat', $data)
                            ->setPaper('a4');
                        $path = config('app.url') . '/storage/documents/'.$name;
                        $pdf->save(public_path("storage/documents/$name"));
                    }
                        break;
                    default: {
                        $data = [
                            "establishment" => $appointment->establishment,
                            "practician" => $appointment->practician,
                            "patient" => $appointment->patient,
                            "metadata" => json_decode(json_encode($metadata))
                        ];
                        $pdf = PDF::loadView('document::certificat.certificat', $data)
                            ->setPaper('a4');
                        $path = config('app.url') . '/storage/documents/'.$name;
                        $pdf->save(public_path("storage/documents/$name"));
                    }
                        break;
                }
            }
        }
        $data = array(
            'type' => $request->type,
            'path' => $path,
            'filename' => $name,
            'patient_id' => $request->patient_id,
            'appointment_id' => $request->appointment_id,
            'metadata' => $request->metadata,
            'created_by' => $createdBy === true ? auth('sanctum')->user()->id : Auth::user()->id,
            'created_by_practician' => $createdBy,
        );

        return array($name, $data);
    }
}
