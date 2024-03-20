<?php

namespace Modules\Appointment\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentNotifRessource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'patient_id' => $this->patient_id,
            'practician_id' => $this->practician_id,
            'establishment_id' => $this->establishment_id,
            'address_id' => $this->address_id,
            'status' => $this->status,
            'motif' => $this->motif,
            'mode' => $this->mode,
            'first_time' => $this->first_time,
            'canceled_at' => $this->canceled_at,
            'resolved_at' => $this->resolved_at,
            'payload' => $this->payload,

            'time_slot' => $this->timeSlot,
        ];
    }
}
