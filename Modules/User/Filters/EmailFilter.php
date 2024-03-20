<?php

namespace Modules\User\Filters;

use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Filters\AbstractFilter;

class EmailFilter extends AbstractFilter
{
    public function filter(Builder $query, $value): Builder
    {
        if ($value === null || strlen($value) <= 2) {
            return $query;
        }

        return $query->where('email', 'like', '%'.$value.'%');
    }
}
