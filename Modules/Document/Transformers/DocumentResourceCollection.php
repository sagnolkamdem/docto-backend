<?php

namespace Modules\Document\Transformers;

use Modules\Core\Transformers\PaginationResourceCollection;

class DocumentResourceCollection extends PaginationResourceCollection
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
            'data' => $this->collection->transform(fn ($document) => new DocumentResource($document)),
            'pagination' => $this->pagination,
            'filters' => $this->filters,
        ];
    }
}
