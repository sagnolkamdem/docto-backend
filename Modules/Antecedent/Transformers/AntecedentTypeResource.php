<?php

namespace Modules\Antecedent\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class AntecedentTypeResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'enabled' => $this->enabled,
        ];
    }
}
