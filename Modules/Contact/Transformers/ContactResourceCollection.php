<?php

namespace Modules\Contact\Transformers;

use Modules\Core\Transformers\PaginationResourceCollection;

class ContactResourceCollection extends PaginationResourceCollection
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
            'data' => $this->collection->transform(fn ($contact) => new ContactResource($contact)),
            'pagination' => $this->pagination,
            'filters' => $this->filters,
        ];
    }
}
