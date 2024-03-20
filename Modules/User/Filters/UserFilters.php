<?php

namespace Modules\User\Filters;

use Modules\Core\Filters\AbstractFilters;
use Modules\Employee\Filters\RoleFilter;


class UserFilters extends AbstractFilters
{
    public array $filters = [
        'search' => SearchFilter::class,
        'name' => NameFilter::class,
        'email' => EmailFilter::class,
        'phone'=> PhoneFilter::class,
        'period' => PeriodFilter::class,
        'status' => StatusFilter::class,
        'is_active' => IsActiveFilter::class,
        'speciality' => SpecialityFilter::class,
        'role' => RoleFilter::class,
    ];
}
