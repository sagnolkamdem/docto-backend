<?php

namespace Modules\Appointment\Filters;

use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Filters\AbstractFilter;

class practicianFilter extends AbstractFilter
{
    public function filter(Builder $query, $value): Builder
    {
        if ($value === null || strlen($value) <= 2) {
            return $query;
        }

        return $query->whereHas('practician', function (Builder $query) use ($value) {
            $query->where('first_name', 'like', '%'.$value.'%') or
            $query->where('last_name', 'like', '%'.$value.'%');
        });
    }
}
