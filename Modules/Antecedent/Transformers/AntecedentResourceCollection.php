<?php

namespace Modules\Antecedent\Transformers;

use Modules\Core\Transformers\PaginationResourceCollection;

class AntecedentResourceCollection extends PaginationResourceCollection
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
            'data' => $this->collection->transform(fn ($antecedent) => new AntecedentResource($antecedent)),
            'pagination' => $this->pagination,
        ];
    }
}
