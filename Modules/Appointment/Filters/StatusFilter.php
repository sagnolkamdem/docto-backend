<?php

namespace Modules\Appointment\Filters;

use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Filters\AbstractFilter;

class statusFilter extends AbstractFilter
{
    public function filter(Builder $query, $value): Builder
    {
        if ($value === null) {
            return $query;
        }

        return $query->where('status', $value);
    }
}
