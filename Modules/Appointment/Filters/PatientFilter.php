<?php

namespace Modules\Appointment\Filters;

use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Filters\AbstractFilter;

class PatientFilter extends AbstractFilter
{
    public function mappings(): array
    {
        return [];
    }

    public function filter(Builder $query, $value): Builder
    {
        if ($value === null || strlen($value) <= 2) {
            return $query;
        }

        return $query->whereHas('patient', function (Builder $query) use ($value) {
            $query->where('first_name', 'like', '%'.$value.'%') or
            $query->where('last_name', 'like', '%'.$value.'%');
        });
    }
}
