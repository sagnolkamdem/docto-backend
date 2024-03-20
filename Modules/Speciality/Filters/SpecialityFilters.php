<?php

namespace Modules\Speciality\Filters;

use Modules\Core\Filters\AbstractFilters;

class SpecialityFilters extends AbstractFilters
{
    public array $filters = [
        'name' => NameFilter::class,
        'status' => StatusFilter::class,
        'guard' => GuardFilter::class
    ];
}
