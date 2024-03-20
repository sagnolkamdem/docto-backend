<?php

namespace Modules\Employee\Transformers;

use Modules\Core\Transformers\PaginationResourceCollection;

class EmployeeResourceCollection extends PaginationResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(fn ($employee) => new EmployeeResource($employee)),
            'pagination' => $this->pagination,
            'filters' => $this->filters,
        ];
    }
}
