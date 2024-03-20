<?php

namespace Modules\Core\Transformers;

use Illuminate\Http\Resources\Json\PaginatedResourceResponse;
use Illuminate\Support\Arr;

class CustomMetaResponse extends PaginatedResourceResponse {
    protected function meta($paginated)
    {
        return Arr::except($paginated, [
            'links', // Add here the links to be excluded from the response
            'data',
            'first_page_url',
            'last_page_url',
            'prev_page_url',
            'next_page_url',
        ]);
    }
}
