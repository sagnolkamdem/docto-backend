<?php

namespace Modules\Core\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginationResourceCollection extends ResourceCollection
{
    public function __construct($resource, public $filters = [])
    {
        $this->pagination = [
            'total' => $resource->total(),
            'per_page' => $resource->perPage(),
            'current_page' => $resource->currentPage(),
            'next_page' => $resource->nextPageUrl(),
            'prev_page' => $resource->previousPageUrl(),
            'first_page' => $resource->url(1),
            'last_page' => $resource->url($resource->lastPage()),
            'from' => $resource->firstItem(),
            'to' => $resource->lastItem(),
            'total_pages' => $resource->lastPage(),
        ];

        $resource = $resource->getCollection();

        parent::__construct($resource);
    }
}
