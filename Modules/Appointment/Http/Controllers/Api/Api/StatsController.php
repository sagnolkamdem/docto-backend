<?php

namespace Modules\Appointment\Http\Controllers\Api\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Appointment\Entities\Appointment;
use Modules\Appointment\Enums\Status;
use Modules\Core\Http\Controllers\Api\CoreController;
use Modules\Practician\Entities\Practician;
use Modules\Practician\Http\Requests\ValidatePracticianRequest;
use Modules\User\Entities\User;

class StatsController extends CoreController
{
    public function getActivity(Request $request)
    {
        return $this->successResponse(__("Got Activity Successfully"), [
            "activity" => [
                "by_motif" => Appointment::where('establishment_id', $request->id)
                    ->select(DB::raw('motif'), DB::raw('count(*) as total'))
                    ->groupBy('motif')
                    ->get(),

                "by_address" => Appointment::where('appointments.establishment_id', $request->id)
                    ->join('addresses', 'appointments.address_id', '=', 'addresses.id')
                    ->select(DB::raw('count(*) as total'), DB::raw('addresses.description as address'))
                    ->groupBy('address')
                    ->get()
            ]
        ]);
    }

    public function dashboard(ValidatePracticianRequest $request)
    {
        return $this->successResponse(__("Got Stats Successfully"), [
            "stats" => [
                "patient" => [
                    "total" => User::query()->whereHas('roles',  function ($query) {
                        $query->where('name', 'patient');
                    })->count(),
                    "actif" => User::query()->whereHas('roles',  function ($query) {
                        $query->where('name', 'patient');
                    })->where('status','=', true)->count(),
                    "inactif" => User::query()->whereHas('roles',  function ($query) {
                        $query->where('name', 'patient');
                    })->where('status','=', false)->count(),
                ],

                "practician" => [
                    "total" => Practician::query()->whereHas('roles',  function ($query) {
                        $query->where('name', 'practician');
                    })->count(),
                    "actif" => Practician::query()->whereHas('roles',  function ($query) {
                        $query->where('name', 'practician');
                    })->where('is_active','=',true)->count(),
                    "inactif" => Practician::query()->whereHas('roles',  function ($query) {
                        $query->where('name', 'practician');
                    })->where('is_active','=',false)->count(),
                ],

                "system" => [
                    "total" => Appointment::query()->count(),
                    "solved" => Appointment::query()->where('status','=',Status::SOLVED)->count(),
                    "canceled" => Appointment::query()->where('status','=',Status::CANCEL)->count(),
                    'in_progress' => Appointment::query()->where('status','=',Status::IN_PROGRESS)->count(),
                    'new' => Appointment::query()->where('status','=',Status::NEW)->count(),
                ]
            ]
        ]);
    }
}
