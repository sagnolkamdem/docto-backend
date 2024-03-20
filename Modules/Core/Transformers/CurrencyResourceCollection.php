<?php

namespace Modules\Core\Transformers;

use Modules\Core\Transformers\PaginationResourceCollection;

class CurrencyResourceCollection extends PaginationResourceCollection
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
            'data' => $this->collection->transform(fn ($core) => new CurrencyResource($core)),
            'pagination' => $this->pagination,
            'filters' => $this->filters,
        ];
    }
}
