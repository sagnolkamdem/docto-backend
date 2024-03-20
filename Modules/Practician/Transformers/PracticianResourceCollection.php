<?php

namespace Modules\Practician\Transformers;

use Modules\Core\Transformers\PaginationResourceCollection;

class PracticianResourceCollection extends PaginationResourceCollection
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
            'data' => $this->collection->transform(fn ($practician) => new PracticianResource($practician)),
            'pagination' => $this->pagination,
            'filters' => $this->filters,
        ];
    }
}
