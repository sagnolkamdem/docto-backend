<?php

namespace Modules\Core\Filters;

use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Filters\AbstractFilter;

class IsActifFilter extends AbstractFilter
{
    public function mappings(): array
    {
        return [
            "true" => 1,
            "false" => 0
        ];
    }

    public function filter(Builder $query, $value): Builder
    {
        $value = $this->resolveFilterValue($value);
        return $query->where('is_active', '=', $value );
    }
}
