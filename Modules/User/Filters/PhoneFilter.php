<?php

namespace Modules\User\Filters;

use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Filters\AbstractFilter;

class PhoneFilter extends AbstractFilter
{
    public function filter(Builder $query, $value): Builder
    {
        if ($value === null || strlen($value) <= 4) {
            return $query;
        }

        return $query->where('phone_number', 'like', '%'.$value.'%');
    }

}
