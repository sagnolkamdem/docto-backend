<?php

namespace Modules\Core\Filters;

use Modules\Core\Filters\AbstractFilters;

class CommunesFilters extends AbstractFilters
{
    public array $filters = [
        'name' => NameFilter::class
    ];
}
