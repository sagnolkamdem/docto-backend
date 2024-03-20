<?php

namespace Modules\Speciality\Transformers;

use Modules\Core\Transformers\PaginationResourceCollection;

class SpecialityResourceCollection extends PaginationResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
//        return parent::toArray($request);
        return [
            'data' => $this->collection->transform(fn ($speciality) => new SpecialityResource($speciality)),
            'pagination' => $this->pagination,
            'filters' => $this->filters,
        ];
    }
}
