<?php

namespace Modules\Core\Filters;

use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Filters\AbstractFilter;

class NameFilter extends AbstractFilter
{
    public function filter(Builder $query, $value): Builder
    {
        if (strlen($value) < 3) {
            return $query;
        }

        return $query->where('nom', 'like', '%' . $value . '%');
    }
}
