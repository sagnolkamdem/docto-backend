<?php

namespace Modules\Core\Filters;

use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Filters\AbstractFilter;

class IsCurrencyFilter extends AbstractFilter
{
    public function filter(Builder $query, $value): Builder
    {
        return $query->where('currencies', 'like', '%' . $value . '%');
    }
}
