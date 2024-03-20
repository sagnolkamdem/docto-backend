<?php

namespace Modules\Motif\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class MotifResource extends JsonResource
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
            'description' => $this->description,
            'enabled' => $this->enabled,
            'practician_id' => $this->practician_id,
            'practician' => $this->practician,
        ];
    }
}
