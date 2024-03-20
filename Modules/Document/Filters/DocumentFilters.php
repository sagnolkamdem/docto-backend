<?php

namespace Modules\Document\Filters;

use Modules\Core\Filters\AbstractFilters;
use Modules\Establishment\Filters\TypeFilter;

class DocumentFilters extends AbstractFilters
{
    public array $filters = [
        'search' => SearchFilter::class,
        'type' => TypeFilter::class
    ];
}
