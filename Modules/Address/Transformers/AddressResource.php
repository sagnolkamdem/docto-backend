<?php

namespace Modules\Address\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'status' => $this->status,
            'practician_id' => $this->practician_id,
            'establishment_id' => $this->establishment_id,
            'description' => $this->description,
            'commune_id' => $this->commune_id,
            'address_lines' => $this->address_lines,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
