<?php

namespace Modules\Core\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
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
            'name_official' => $this->name_official,
            'is_active' => $this->is_active,
            'is_enabled' => $this->is_enabled,
            'cca3' => $this->cca3,
            'cca2' => $this->cca2,
            'flag' => $this->flag,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'currencies' => $this->currencies,
            'callingCodes' => $this->callingCodes
        ];
    }
}
