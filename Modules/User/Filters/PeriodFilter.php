<?php
namespace Modules\User\Filters;

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
        return $query->whereBetween('users.created_at',[$value[0],$value[1]]);
    }
}
