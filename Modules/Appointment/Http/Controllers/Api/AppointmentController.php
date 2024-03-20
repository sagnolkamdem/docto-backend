<?php

namespace Modules\Appointment\Http\Controllers\Api;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Modules\Appointment\Emails\CancelAppointmentMail;
use Modules\Appointment\Emails\EndAppointmentMail;
use Modules\Appointment\Emails\NewAppointmentMail;
use Modules\Appointment\Emails\PostponeAppointmentMail;
use Modules\Appointment\Entities\Appointment;
use Modules\Appointment\Enums\Status;
use Modules\Appointment\Events\NewAppointment;
use Modules\Appointment\Http\Requests\CreateAppointmentRequest;
use Modules\Appointment\Http\Requests\GetAppointmentsRequest;
use Modules\Appointment\Http\Requests\TransferRequest;
use Modules\Appointment\Transformers\AppointmentRessource;
use Modules\Appointment\Transformers\AppointmentRessourceCollection;
use Modules\Appointment\Transformers\ConsultationResource;
use Modules\Appointment\Transformers\TransferedResource;
use Modules\Appointment\Transformers\TransferedResourceCollection;
use Modules\Authentication\Helpers\SMSObject;
use Modules\Core\Http\Controllers\Api\CoreController;
use Modules\Document\Entities\Document;
use Modules\Document\Transformers\DocumentResourceCollection;
use Modules\Establishment\Entities\Establishment;
use Modules\Establishment\Transformers\EstablishmentResource;
use Modules\Practician\Entities\Practician;
use Modules\Practician\Transformers\AvailabilityResource;
use Modules\Practician\Transformers\PracticianResourceCollection;
use Modules\Practician\Transformers\ProfilePracticianResource;
use Modules\Speciality\Entities\Speciality;
use Modules\Speciality\Transformers\SpecialityResource;
use Modules\TimeSlot\Entities\TimeSlot;
use Modules\User\Entities\User;

class AppointmentController extends CoreController
{
    public function index(GetAppointmentsRequest $request)
    {
        $perPage = $request->query('per_page', 10);
        $id = $request->patient_id??auth()->user()->id;
        $appointments = Appointment::query()
            ->where('patient_id', $id)
            ->orWhereHas('patient', function ($query) use ($id) {
                $query->where('parent_id', $id);
            })
            ->orderBy('created_at', 'desc')
            ->filter($request)
            ->paginate($perPage);

        return $this->json(
            new AppointmentRessourceCollection($appointments)
        );
    }

    public function store(CreateAppointmentRequest $request)
    {
        $timeSlot = TimeSlot::query()
            ->where('practician_id', $request->input('practician_id'))
            ->where('payload->start_time', $request->input('start_time'))
            ->where('payload->date', $request->date)
            ->first();

        if (!$timeSlot || $timeSlot->appointment_id != null) {
            return $this->errorResponse('La tranche horaire n\'est pas disponible.');
        }
        $appointment = [
            'patient_id' => $request->patient_id??auth()->user()->id,
            'practician_id' => $request->practician_id,
            'establishment_id' => $request->establishment_id,
            'address_id' => $request->address_id,
            'payload' => $request->payload,
            'motif' => $request->motif,
            'mode' => $request->mode,
            'first_time' => $request->first_time,
        ];

        $appointment = Appointment::create($appointment);

        $timeSlot->appointment_id = $appointment->id;
        $timeSlot->status = false;
        $timeSlot->save();

        $user = User::findOrFail($appointment->patient_id);

        if ($user->parent != null) {
            Mail::send(new NewAppointmentMail($user->parent, $appointment));
        }

        if ($user->email!= null) {
            Mail::send(new NewAppointmentMail($user, $appointment));
        }
        Mail::send(new NewAppointmentMail($appointment->practician, $appointment, true));

        broadcast(new NewAppointment(User::findOrFail($request->patient_id??auth()->user()->id),$appointment))->toOthers();
        return $this->successResponse(
            __('Your appointment has been successfully created.'),
            ['appointment' => new AppointmentRessource($appointment)]
        );
    }

    public function show($id)
    {
        $appointment = Appointment::findOrFail($id);

        return $this->successResponse(
            __('Get appointment successfully'),
            ['consultation' => new ConsultationResource($appointment)]
        );
    }

    public function getByUser(Request $request, $id)
    {
        $appointment = User::where('id',$id)->first();

        return $this->successResponse(
            __('Get appointments successfully'),
            ['appointments' => new AppointmentRessourceCollection($appointment->appointments()->filter($request)->orderBy('created_at','desc')->paginate($request->per_page ?? 10))]
        );
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        if($appointment->status == Status::CANCEL) {
            return $this->errorResponse("This appointment has already been canceled");
        }

        if($appointment->status == Status::SOLVED) {
            return $this->errorResponse("This appointment has already been ended");
        }

        $data = [
            'patient_id' => $request->patient_id,
            'practician_id' => $request->practician_id,
            'establishment_id' => $request->establishment_id,
            'address_id' => $request->address_id,
            'payload' => $request->payload,
            'motif' => $request->motif,
            'mode' => $request->mode,
            'first_time' => $request->first_time,
        ];
        $data = array_filter($data);
        $appointment->update($data);

        return $this->successResponse(
            __('Update appointment successfully'),
            ['appointment' => new AppointmentRessource($appointment)]
        );
    }

    public function postpone(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        if($appointment->status == Status::CANCEL) {
            return $this->errorResponse("This appointment has already been canceled");
        }

        if($appointment->status == Status::SOLVED) {
            return $this->errorResponse("This appointment has already been ended");
        }

        $timeSlot = TimeSlot::query()
            ->where('practician_id', $appointment->practician_id)
            ->where('payload->start_time', 'like', "%".$request->start_time."%")
            ->where('payload->date', $request->date)
            ->first();

        if (!$timeSlot) {
            return $this->errorResponse('La tranche horaire n\'est pas disponible.');
        }

        if ($timeSlot->payload['start_time'] == $appointment->timeSlot->payload['start_time']) {
            return $this->errorResponse('La tranche horaire est la meme.');
        }

        $old = $appointment;
        $appointment->timeSlot->update([
            'status' => true,
            'appointment_id' => null,
        ]);

        $timeSlot->update([
            'status' => false,
            'appointment_id' => $appointment->id,
        ]);
        $user = User::findOrFail($appointment->patient_id);
        if ($user->email != null){
            Mail::send(new PostponeAppointmentMail($user, $appointment, $old));
        }

        return $this->successResponse(
            __('Postpone appointment successfully'),
            ['appointment' => new AppointmentRessource($appointment)]
        );
    }

    public function updateStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        if($appointment->status == Status::CANCEL) {
            return $this->errorResponse("This appointment has already been canceled");
        }

        if($appointment->status == Status::SOLVED) {
            return $this->errorResponse("This appointment has already been ended");
        }

        switch ($request->status) {
            case Status::CANCEL:
                $data = [
                    'status' => Status::CANCEL,
                    'canceled_at' => now(),
                    'canceled_by' => $request->user()->id
                ];
                break;
            case Status::SOLVED:
                $data = [
                    'status' => Status::SOLVED,
                    'resolved_at' => now(),
                ];
                break;
            case Status::IN_PROGRESS:
                $data = [
                    'status' => Status::IN_PROGRESS,
                ];
                break;
            case Status::PENDING:
                $data = [
                    'status' => Status::PENDING,
                ];
                break;
            default:
                break;
        }

        $appointment->update($data);

        $data = [
            'practician_id' => $request->practician_id,
            'establishment_id' => $request->establishment_id,
            'address_id' => $request->address_id,
            'payload' => $request->payload,
            'motif' => $request->motif,
            'mode' => $request->mode,
            'first_time' => $request->first_time,
        ];
        $data = array_filter($data);
        $appointment->update($data);

        $user = User::findOrFail($appointment->patient_id);
        if ($user->email != null){
            if($request->status == Status::CANCEL) {
                $timeSlot = $appointment->timeSlot;
                $timeSlot->appointment_id = null;
                $timeSlot->status = true;
                $timeSlot->save();
                Mail::send(new CancelAppointmentMail($user, $appointment, $request->cancel_motif));
            }

            if($request->status == Status::SOLVED) {
                Mail::send(new EndAppointmentMail($user, $appointment));
            }
        }


        return $this->successResponse(
            __('Update appointment status successfully'),
            ['appointment' => new AppointmentRessource($appointment)]
        );
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();
        return $this->successResponse(__('Deleted appointment successfully!'));
    }

    public function getAllPracticians(Request $request)
    {
        $practicians = Practician::query()
            ->filter($request)
            ->paginate($request->get('per_page', 10));

        return $this->json([new PracticianResourceCollection($practicians)]);
    }

    public function showPractician(Request $request, $id)
    {
        $practician = Practician::findOrFail($id);

        return $this->successResponse(__('Gets practician successfully'), [
            'practician' => new ProfilePracticianResource($practician)
        ]);
    }

    public function search(Request $request) {
        $commune = $request->commune;
        $wilaya = $request->wilaya;
        if ($request->search === null || strlen($request->search) < 2) {
            return $this->successResponse(
                __('Gets result successfully'),
                [
                    'practicians' => [],
                    'establishments' => [],
                    'specialities' => [],
                ]
            );
        }
        $practicians = Practician::query()->where('is_active', true)
            ->where(function (Builder $query) use ($request) {
                $query->where('first_name','like', '%'.$request->search.'%')
                    ->orWhere('last_name','like', '%'.$request->search.'%');
            })
            ->when($request->commune, function (Builder $query) use ($commune) {
                $query->whereHas('addresses', function (Builder $query) use ($commune) {
                    $query->whereHas('commune',function (Builder $query) use ($commune) {
                        $query->where('nom', 'like', '%' . $commune.'%');
                    });
                });
            })
            ->when($request->wilaya, function (Builder $query) use ($wilaya) {
                $query->whereHas('addresses', function (Builder $query) use ($wilaya) {
                    $query->whereHas('commune',function (Builder $query) use ($wilaya) {
                        $query->whereHas('wilaya',function (Builder $query) use ($wilaya) {
                            $query->where('nom', 'like', '%' . $wilaya.'%');
                        });
                    });
                });
            })
            ->get();
        $establishments = Establishment::query()
            ->where(function (Builder $query) use ($request) {
                $query->where('name','like', '%'.$request->search.'%');
            })
            ->when($request->commune, function (Builder $query) use ($commune) {
                $query->whereHas('addresss', function (Builder $query) use ($commune) {
                    $query->whereHas('commune',function (Builder $query) use ($commune) {
                        $query->where('nom', 'like', '%' . $commune.'%');
                    });
                });
            })
            ->when($request->wilaya, function (Builder $query) use ($wilaya) {
                $query->whereHas('addresss', function (Builder $query) use ($wilaya) {
                    $query->whereHas('commune',function (Builder $query) use ($wilaya) {
                        $query->whereHas('wilaya',function (Builder $query) use ($wilaya) {
                            $query->where('nom', 'like', '%' . $wilaya.'%');
                        });
                    });
                });
            })
            ->get();
        $specialities = Speciality::with(['practicians' => function ($query) use ($request, $commune, $wilaya) {
                $query->where('is_active', true)
                    ->when($request->commune, function (Builder $query) use ($commune) {
                            $query->whereHas('addresses', function (Builder $query) use ($commune) {
                                $query->whereHas('commune',function (Builder $query) use ($commune) {
                                    $query->where('nom', 'like', '%' . $commune.'%');
                                });
                        });
                    })
                    ->when($request->wilaya, function (Builder $query) use ($wilaya) {
                            $query->whereHas('addresses', function (Builder $query) use ($wilaya) {
                                $query->whereHas('commune',function (Builder $query) use ($wilaya) {
                                    $query->whereHas('wilaya',function (Builder $query) use ($wilaya) {
                                        $query->where('nom', 'like', '%' . $wilaya.'%');
                                    });
                                });
                            });
                        });
            }])
            ->where(function (Builder $query) use ($request) {
                $query->where('name','like', '%'.$request->search.'%');
            })
            ->get();

        return $this->successResponse(
            __('Gets result successfully'),
            [
                'practicians' => ProfilePracticianResource::collection($practicians),
                'establishments' => EstablishmentResource::collection($establishments),
                'specialities' => SpecialityResource::collection($specialities),
            ]
        );
    }

    public function bestPracticians(GetAppointmentsRequest $request)
    {
        $id = $request->patient_id??$request->user()->id;
        $appointments = DB::table('appointments')
            ->select('practicians.*','specialities.name as speciality', DB::raw('count(*) as total_appointments'))
            ->join('practicians', 'appointments.practician_id', '=', 'practicians.id')
            ->join('specialities', 'practicians.speciality', '=', 'specialities.id')
            ->where('appointments.patient_id', '=', $id)
            ->groupBy('practicians.id')
            ->orderByDesc('total_appointments')
            ->get();

        return $this->json(
            $appointments
        );
    }

    public function availableSlots(Request $request)
    {
        $practicianId = $request->get('practician');
        $date = $request->get('date');

        $doctor = Practician::findOrFail($practicianId);
        $slots = $doctor->availableSlots($date, $request->get('period'));
        return $this->successResponse(__('Got available slots successfully'), [
//            'time_slots' => $slots,
            'practician_id' => $practicianId,
            'time_slots' => [
                'payload' => $this->values($slots)
            ]
        ]);
    }

    public function unavailableSlots(Request $request)
    {
        $practicianId = $request->get('practician');
        $date = $request->get('date');

        $doctor = Practician::findOrFail($practicianId);
        $slots = $doctor->unavailableSlots($date, $request->get('period'));

        return $this->successResponse(__('Got available slots successfully'), [
            'practician' => $doctor,
            'time_slots' => [
                'payload' => $this->values($slots)
            ]
        ]);
    }

    public function values($slots) {
        $arr = [];
        foreach ($slots as $key => $slot){
            $arr[] = [
                'date' => $key,
                'slots' => AvailabilityResource::collection($slot),
            ];
        }
        return $arr;
    }

    public function unavailableSlotsByEstablishment(Request $request)
    {
        $establishmentId = $request->id;
        $establishment = Establishment::findOrFail($establishmentId);
        $date = $request->get('date');

        $doctors = Practician::with('roles')
            ->whereHas('roles',  function ($query) {
                $query->where('name', 'practician');
            })
            ->whereHas('establishments', function (Builder $query) use ($establishmentId) {
            $query->where('id', $establishmentId);
        })->get();
        $practicians = [];
        foreach ($doctors as $doctor) {
            $slots = $doctor->unavailableSlots($date);
            $practicians[] = [
                'practician_id' => $doctor->id,
                'first_name' => $doctor->first_name,
                'last_name' => $doctor->last_name,
                'time_slots' => [
                    'payload' => $this->values($slots)
                ]
            ];
        }
        return $this->successResponse(__('Got available slots successfully'), [
            'establishment' => $establishment,
            'practicians' => $practicians
        ]);
    }

    public function nextSlots(Request $request)
    {
        $date = $request->get('date');
        $doctors = Practician::query()->paginate($request->per_page??10);
        $practicians = [];
        foreach ($doctors as $doctor) {
            $slots = $doctor->timeSlots()
                ->select(['payload->date as date','payload->start_time as start_time','payload->end_time as end_time','status'])
                ->where('payload->date', '=', $date ?? now()->toDateString())
                ->where('appointment_id', null)
                ->where('status', true)
                ->first();
            if( $slots != null) {
                $time = \Carbon\Carbon::parse($slots->date. " ". $slots->start_time)->diffForHumans();
                $practicians[] = [
                    'practician' => $doctor,
                    'next_slot' => $time
                ];
            }
        }

        return $this->successResponse(__('Got available slots successfully'), [
            'practicians' => $practicians
        ]);
    }

    public function remind(TransferRequest $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $user = User::findOrFail($appointment->patient_id);
        $user2 = Practician::findOrFail($appointment->practician_id);

        if ($user->parent != null) {
//            Mail::send(new NewAppointmentMail($user->parent, $appointment));
            SMSObject::sendMessage(
                "Cher $user->first_name, Tabiblib vous rappelles un rendez-vous medical vous concernant pour le ".$appointment->timeSlot->payload['date']." à ".$appointment->timeSlot->payload['start_time'].".
                        Le rendez-vous sera avec le Docteur ".$appointment->practician->first_name." ".$appointment->practician->last_name.", à ".$appointment->establishment->name .", situé à ".$appointment->address->description.".
                        Pour plus d'info, veuillez contacter votre medecin .",
                $user->parent->phone_number
            );
        }

        SMSObject::sendMessage(
            "Cher $user->first_name, Tabiblib vous rappelles un rendez-vous medical vous concernant pour le ".$appointment->timeSlot->payload['date']." à ".$appointment->timeSlot->payload['start_time'].".
                        Le rendez-vous sera avec le Docteur ".$appointment->practician->first_name." ".$appointment->practician->last_name.", à ".$appointment->establishment->name .", situé à ".$appointment->address->description.".
                        Pour plus d'info, veuillez contacter votre medecin .",
            $user->phone_number
        );

        SMSObject::sendMessage(
            "Cher $user2->first_name, Tabiblib vous rappelles un rendez-vous medical vous concernant pour le ".$appointment->timeSlot->payload['date']." à ".$appointment->timeSlot->payload['start_time'].".
                        Le rendez-vous sera à ".$appointment->establishment->name .", situé à ".$appointment->address->description.".
                        Pour plus d'info, veuillez contacter votre Etablissement Medical.",
            $user2->phone_number
        );
//        if ($user->email!= null) {
//            Mail::send(new NewAppointmentMail($user, $appointment));
//        }
//
//        broadcast(new NewAppointment(User::findOrFail($request->patient_id??auth()->user()->id),$appointment))->toOthers();

        return $this->successResponse(
            __('Transfer appointment successfully'),
            ['appointment' => new AppointmentRessource($appointment)]
        );
    }

    public function transfer(TransferRequest $request, $id)
    {
        $appointment = Appointment::with('timeSlot')->findOrFail($id);
        $documents = $request->documents ?? [];

        //TODO: Controls for unique entries on transfers and docs

        DB::table('transfers')->insert([
            'appointment_id' => $appointment->id,
            'practician_id' => $request->practician_id
        ]);

        if(count($documents) > 0) {
            foreach ($documents as $document_id) {
                DB::table('document_transfers')->insert([
                    'document_id' => $document_id,
                    'appointment_id' => $appointment->id,
                ]);
            }
        }

        return $this->successResponse(
            __('Transfer appointment successfully'),
            ['appointment' => new AppointmentRessource($appointment)]
        );
    }

    public function transfered(Request $request, $id)
    {
        $appointment = Practician::where('id',$id)->first();

        return $this->successResponse(
            __('Get appointments successfully'),
            ['appointments' => new TransferedResourceCollection($appointment->transfers()->paginate($request->per_page ?? 10))]
        );
    }

    public function getDocsByAppointment(Request $request, $id) {
        $appointment = Appointment::findOrFail($id);
        return $this->successResponse(
            __('Get docs successfully'),
            ['documents' => new DocumentResourceCollection($appointment->documents()->filter($request)->paginate($request->per_page ?? 10))]
        );
    }
}
