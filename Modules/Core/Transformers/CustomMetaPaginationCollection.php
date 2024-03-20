<?php

namespace Modules\Core\Transformers;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CustomMetaPaginationCollection extends AnonymousResourceCollection
{
    protected function preparePaginatedResponse($request)
    {
        if ($this->preserveAllQueryParameters) {
            $this->resource->appends($request->query());
        } elseif (! is_null($this->queryParameters)) {
            $this->resource->appends($this->queryParameters);
        }
        return (new CustomMetaResponse($this))->toResponse($request);
    }
}
