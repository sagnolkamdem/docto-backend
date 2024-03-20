<?php

namespace Modules\Employee\Filters;

use Modules\Core\Filters\AbstractFilters;

class EmployeeFilters extends AbstractFilters
{
    public array $filters = [
        'role' => RoleFilter::class,
        'status' => StatusFilter::class,
        'search' => SearchFilter::class,
    ];
}
