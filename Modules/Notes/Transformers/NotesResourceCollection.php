<?php

namespace Modules\Notes\Transformers;

use Modules\Core\Transformers\PaginationResourceCollection;

class NotesResourceCollection extends PaginationResourceCollection
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
            'data' => $this->collection->transform(fn ($notes) => new NotesResource($notes)),
            'pagination' => $this->pagination,
            'filters' => $this->filters,
        ];
    }
}
