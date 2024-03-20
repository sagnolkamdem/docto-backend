<?php

namespace Modules\TimeSlot\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Http\Controllers\Api\CoreController;
use Modules\TimeSlot\Entities\TimeSlot;
use Modules\TimeSlot\Http\Requests\CreateTimeSlotRequest;

class TimeSlotController extends CoreController
{
    public function createForPractician(CreateTimeSlotRequest $request)
    {
        if ($request->time_slots){
            foreach ($request->time_slots as $time_slot) {
                $timeSlots[] = [
                    'practician_id' => $request->practician_id,
//                    'establishment_id' => $request->establishment_id,
                    'payload' => json_encode($time_slot['payload']),
                    'description' => $request->description,
                ];
            }

            $timeSlots = TimeSlot::insert($timeSlots);
            return $this->successResponse(
                __('Your timeSlot has been successfully created.'),
                ['timeSlot' => $timeSlots]
            );
        }
        $timeSlot = [
            'practician_id' => $request->practician_id,
            'establishment_id' => $request->establishment_id,
            'payload' => $request->payload,
            'description' => $request->description,
        ];

        $timeSlot = TimeSlot::create($timeSlot);
        return $this->successResponse(
            __('Your timeSlot has been successfully created.'),
            ['timeSlot' => $timeSlot]
        );
    }

    public function getAll(Request $request)
    {
        $timeSlotes = TimeSlot::paginate($request->query('per_page', 10));

        return $this->json($timeSlotes);
    }

    public function show(Request $request, $id)
    {
        $timeSlot = TimeSlot::findOrFail($id);

        return $this->successResponse(
            __('Get timeSlot successfully'),
            ['timeSlot' => $timeSlot]
        );
    }

    public function update(Request $request, $id)
    {
        $timeSlot = TimeSlot::findOrFail($id);
        $data = [
            'description' => $request->description,
            'payload' => $request->payload,
        ];

        $timeSlot->update($data);

        return $this->successResponse(
            __('Update timeSlot successfully'),
            ['timeSlot' => $timeSlot]
        );
    }

    public function destroy(Request $request,$id)
    {
        $timeSlot = TimeSlot::findOrFail($id);

//        $this->authorize(Policy::DELETE, $timeSlot);

        $timeSlot->delete();

        return $this->successResponse(__('Deleted timeSlot successfully!'));
    }
}
