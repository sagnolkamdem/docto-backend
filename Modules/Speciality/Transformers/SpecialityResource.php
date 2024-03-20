<?php

namespace Modules\Speciality\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Practician\Transformers\ProfilePracticianResource;

class SpecialityResource extends JsonResource
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
            'code' => $this->code,
            'status' => $this->status,
            'slug' => $this->slug,
            'avatar' => base64_encode($this->avatar),
            'practicians' => ProfilePracticianResource::collection($this->practicians),
        ];
    }
}
