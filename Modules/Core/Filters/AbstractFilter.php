<?php

namespace Modules\Core\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

abstract class AbstractFilter
{
    abstract public function filter(Builder $query, $value): Builder;

    public function mappings(): array
    {
        return [];
    }

    protected function resolveFilterValue($key)
    {
        return Arr::get($this->mappings(), $key);
    }
}
