<?php

namespace Modules\Address\Transformers;

use Modules\Core\Transformers\PaginationResourceCollection;

class AddressResourceCollection extends PaginationResourceCollection
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
            'data' => $this->collection->transform(fn ($address) => new AddressResource($address)),
            'pagination' => $this->pagination,
            'filters' => $this->filters,
        ];
    }
}
