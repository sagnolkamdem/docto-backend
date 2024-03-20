<?php

namespace Modules\User\Transformers;

use Modules\Core\Transformers\PaginationResourceCollection;

class PatientResourceCollection extends PaginationResourceCollection
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
            'data' => $this->collection->transform(fn ($user) => new ProfilePatientResource($user)),
            'pagination' => $this->pagination,
            'filters' => $this->filters,
        ];
    }
}
