<?php

namespace Modules\Establishment\Transformers;

use Modules\Core\Transformers\PaginationResourceCollection;

class EstablishmentResourceCollection extends PaginationResourceCollection
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
            'data' => $this->collection->transform(fn ($establishment) => new EstablishmentResource($establishment)),
            'pagination' => $this->pagination,
            'filters' => $this->filters,
        ];
    }
}
