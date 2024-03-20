<?php

namespace Modules\User\Filters;

use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Filters\AbstractFilter;

class SearchFilter extends AbstractFilter
{
    public function filter(Builder $query, $value): Builder
    {
        if ($value === null || strlen($value) <= 2) {
            return $query;
        }

        return $query
            ->where('first_name', 'like', '%'.$value.'%')
            ->orWhere('last_name', 'like', '%'.$value.'%')
            ->orWhere('phone_number', 'like', '%'.$value.'%')
            ->orWhere('email', 'like', '%'.$value.'%');
    }
}
