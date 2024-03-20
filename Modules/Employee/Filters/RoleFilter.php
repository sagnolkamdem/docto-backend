<?php

namespace Modules\Employee\Filters;

use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Filters\AbstractFilter;

class RoleFilter extends AbstractFilter
{
    public function filter(Builder $query, $value): Builder
    {
        if (strlen($value) <= 2) {
            return $query;
        }

        return $query->whereHas('roles', function (Builder $query) use ($value) {
            $query->where('name', 'like', '%' . $value . '%');
        });
    }
}
