<?php

namespace Modules\Core\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CountryResourceCollection extends PaginationResourceCollection
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
            'data' => $this->collection->transform(fn ($transaction) => new CountryResource($transaction)),
            'pagination' => $this->pagination,
            'filters' => $this->filters,
        ];
    }
}
