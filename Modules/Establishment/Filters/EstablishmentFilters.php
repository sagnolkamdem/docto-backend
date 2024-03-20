<?php

namespace Modules\Establishment\Filters;

use Modules\Core\Filters\AbstractFilters;

class EstablishmentFilters extends AbstractFilters
{
    public array $filters = [
        'type' => TypeFilter::class,
        'name' => NameFilter::class,
        'city' => CityFilter::class,
        'postal_code'=> PostalCodeFilter::class,
        'practician' => PracticianFilter::class,
        'status' => StatusFilter::class,
    ];
}
