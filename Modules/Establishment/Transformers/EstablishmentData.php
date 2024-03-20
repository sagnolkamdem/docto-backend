<?php

namespace Modules\Establishment\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class EstablishmentData extends JsonResource
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
            'name' => $this->name,
            'type' => $this->type,
            'city' => $this->city,
            'admin_practician' => $this->admin_practician,
            'address' => $this->address,
            'postal_code' => $this->postal_code,
            'description' => $this->description,
            'status' => $this->status,
            'slug' => $this->slug,
            'emergency' => $this->emergency,
            'head_quarter' => $this->head_quarter,
            'time_slots' => $this->time_slots,
            'addresses' => $this->addresss,
        ];
    }
}
