<?php

namespace Modules\Relative\Transformers;

use Modules\Core\Transformers\PaginationResourceCollection;

class RelativeResourceCollection extends PaginationResourceCollection
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
            'data' => $this->collection->transform(fn ($relative) => new RelativeResource($relative)),
            'pagination' => $this->pagination,
        ];
    }
}
