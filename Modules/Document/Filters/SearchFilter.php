<?php

namespace Modules\Document\Filters;

use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Filters\AbstractFilter;

class SearchFilter extends AbstractFilter
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

        return $query
            ->where('type', 'like', '%'.$value.'%')
            ->orWhere('filename', 'like', '%'.$value.'%');
    }
}
