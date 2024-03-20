<?php

namespace Modules\User\Transformers;

use Modules\Core\Transformers\PaginationResourceCollection;

class RoleResourceCollection extends PaginationResourceCollection
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
            'data' => $this->collection->transform(fn ($user) => new RoleResource($user)),
            'pagination' => $this->pagination,
        ];
    }
}
