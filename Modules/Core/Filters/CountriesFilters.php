<?php

namespace Modules\Core\Filters;

use Illuminate\Database\Eloquent\Builder;

class CountriesFilters extends AbstractFilters
{
    public array $filters = [
        'name' => IsNameFilter::class,
        'is_enabled' => IsEnabledFilter::class,
        'is_active' => IsActifFilter::class,
        'currency' => IsCurrencyFilter::class,
    ];
}
