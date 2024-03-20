<?php

namespace Modules\Notes\Filters;

use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Filters\AbstractFilter;

class PeriodFilter extends AbstractFilter
{
    public function mappings(): array
    {
        return [];
    }

    public function filter(Builder $query, $value): Builder
    {
        if ($value === null) {
            return $query;
        }
        $value = explode(';', $value);
        return $query->whereBetween('notes.created_at',[$value[0],$value[1]]);
    }
}
