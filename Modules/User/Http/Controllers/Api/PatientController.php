<?php

namespace Modules\User\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\Antecedent\Entities\Antecedent;
use Modules\Appointment\Entities\Appointment;
use Modules\Appointment\Enums\Status;
use Modules\Appointment\Transformers\AppointmentRessourceCollection;
use Modules\Authentication\Helpers\SMSObject;
use Modules\Authentication\Http\Requests\Api\RegisterRequest;
use Modules\Core\Http\Controllers\Api\CoreController;
use Modules\Establishment\Entities\Establishment;
use Modules\Practician\Entities\Practician;
use Modules\Practician\Transformers\PracticianResource;
use Modules\TimeSlot\Entities\TimeSlot;
use Modules\User\Emails\VerifyEmailMail;
use Modules\User\Entities\User;
use Modules\User\Http\Requests\Api\PatientsRequest;
use Modules\User\Transformers\PatientResourceCollection;
use Modules\User\Transformers\ProfilePatientResource;
use Modules\User\Transformers\WaitListCollection;
use Modules\User\Transformers\WaitListUsers;

class PatientController extends CoreController
{

    public function store(Request $request)
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email??null,
            'password' => Hash::make($request->password??12345678),
            'gender' => $request->gender??null,
            'birthdate' => $request->birthdate??null,
            'phone_number' => $request->phone_number??null,
            'weight' => $request->weight??null,
            'height' => $request->height??null,
            'created_by' => auth('sanctum')->user()->id ?? null
        ]);

        $user->assignRole('patient');

        if($request->antecedents) {
            foreach($request->antecedents as $antecedent) {
                $antecedent['user_id'] = $user->id;
                Antecedent::create($antecedent);
            }
        }

        if($user->email != null) {
            Mail::send(new VerifyEmailMail($user));
        }

        if($user->phone_number != null) {
            SMSObject::sendOTPVerificationCode($request->phone_number);
        }

        return $this->successResponse(
            __('Your user account has been successfully created. A verification email has been sent to you.'),
            ['patient' => $user]
        );
    }

    public function index(PatientsRequest $request)
    {
        $patients = User::with('roles')
            ->whereHas('roles',  function ($query) {
                $query->where('name', 'patient');
            })
            ->filter($request)
            ->orderBy("created_at",'desc')
            ->paginate($request->get('per_page', 10));

        return $this->successResponse(__('Gets all patients successfully'), [
            'patients' => new PatientResourceCollection($patients)
        ]);
    }

    public function show(PatientsRequest $request, $id)
    {
        $patient = User::findOrFail($id);

        return $this->successResponse(__('Gets patient successfully'), [
            'patient' => new ProfilePatientResource($patient)
        ]);
    }

    public function activate(Request $request, $id) {
        $patient = User::findOrFail($id);

        $patient->update([
            'status' => !$patient->status
        ]);

        return $this->successResponse(__('Activated/Deactivated patient successfully'), [
            'patient' => new ProfilePatientResource($patient)
        ]);
    }

    public function update(Request $request, $id)
    {
        $patient = User::findOrFail($id);

        $patient->update([
            'status' => !$patient->status
        ]);

        return $this->successResponse(__('Updated patient successfully'), [
            'patient' => new ProfilePatientResource($patient)
        ]);
    }

    public function getByEstablishment(Request $request, $id)
    {
        $patients = User::whereHas('appointments', function ($query) use ($id) {
            $query->where('establishment_id', '=', $id);
        })->filter($request)
            ->paginate($request->perPage??10);

        return $this->successResponse('Get Establishment patients successfully', [
            'patients' => new PatientResourceCollection($patients)
        ]);
    }

    public function getByPractician(Request $request, $id)
    {
        $patients = User::whereHas('appointments', function ($query) use ($id) {
            $query->where('practician_id', '=', $id);
        })->orWhere('created_by', '=', $id)
          ->orderBy('created_at', 'desc')
            ->filter($request)->paginate($request->perPage??10);

        return $this->successResponse('Get Doctor waitlist successfully', [
            'patients' => new PatientResourceCollection($patients)
        ]);
    }

    public function getWaitListByPractician(Request $request, $id)
    {
        $list = Appointment::with('patient')
            ->where('practician_id', '=', $id)
            ->whereIn('status', [Status::NEW, Status::PENDING])
            ->whereHas('timeSlot', function ($query) {
                $query->where('payload->date', '>=', now()->toDateString());
            })
            ->filter($request)
            ->paginate($request->perPage??10);

        return $this->successResponse('Get Doctor\'s waitlist successfully', [
            'list' => new WaitListCollection($list)
        ]);
    }

    public function getWaitListByEstablishment(Request $request, $id)
    {
        $list = Appointment::with('patient')
            ->where('establishment_id', '=', $id)
            ->whereIn('status', [Status::NEW, Status::PENDING])
            ->whereHas('timeSlot', function ($query) {
                $query->where('payload->date', '>=', now()->toDateString());
            })
            ->filter($request)
            ->paginate($request->perPage??10);

        return $this->successResponse('Get Establishment\'s patients successfully', [
            'list' => new WaitListCollection($list)
        ]);
    }

    public function getConsultationsByPractician(Request $request, $id)
    {
        $list = Appointment::with('patient')
            ->where('practician_id', '=', $id)
            ->whereIn('status', [Status::NEW, Status::PENDING, Status::SOLVED, Status::IN_PROGRESS])
            ->filter($request)
            ->paginate($request->perPage??10);

        return $this->successResponse('Get Doctor\'s consultations successfully', [
            'list' => new WaitListCollection($list)
        ]);
    }

    public function getConsultationsByEstablishment(Request $request, $id)
    {
        $list = Appointment::with('patient')
            ->where('establishment_id', '=', $id)
            ->whereIn('status', [Status::NEW, Status::PENDING, Status::SOLVED, Status::IN_PROGRESS])
            ->filter($request)
            ->paginate($request->perPage??10);

        return $this->successResponse('Get Establishment\'s consultations successfully', [
            'list' => new WaitListCollection($list)
        ]);
    }

    public function destroy($id)
    {
        //
    }
}
