<?php

namespace Modules\Appointment\Filters;

use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Filters\AbstractFilter;

class PeriodFilter extends AbstractFilter
{
    public function filter(Builder $query, $value): Builder
    {
        if ($value === null) {
            return $query;
        }

        $value = explode(';', $value);

        return $query->whereHas('timeSlot', function ($query) use ($value) {
                $query->whereBetween('payload->date',[$value[0], $value[1]]);
            });
    }
}
