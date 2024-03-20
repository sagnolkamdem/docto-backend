<?php

namespace Modules\Relative\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Transformers\ProfilePatientResource;

class RelativeResource extends JsonResource
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
            'type' => $this->type,
            'gender' => $this->gender,
            'birthdate' => $this->birthdate,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'place_of_birth' => $this->place_of_birth,
            'address' => $this->address,
            'height' => $this->height,
            'weight' => $this->weight,
            'is_patient' => $this->is_patient,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'patient' => new ProfilePatientResource($this->patient),
            'parent' => new ProfilePatientResource($this->parent)
        ];
    }
}
