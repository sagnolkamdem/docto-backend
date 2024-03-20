<?php

namespace Modules\Chat\Filters;

use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Filters\AbstractFilter;

class NameFilter extends AbstractFilter
{
    public function mappings(): array
    {
        return [];
    }

    public function filter(Builder $query, $value): Builder
    {
        return $query;
    }
}
