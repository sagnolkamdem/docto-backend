<?php

namespace Modules\Motif\Transformers;

use Modules\Core\Transformers\PaginationResourceCollection;

class MotifResourceCollection extends PaginationResourceCollection
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
            'data' => $this->collection->transform(fn ($motif) => new MotifResource($motif)),
            'pagination' => $this->pagination,
            'filters' => $this->filters,
        ];
    }
}
