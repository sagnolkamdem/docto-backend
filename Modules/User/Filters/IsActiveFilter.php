<?php

namespace Modules\User\Filters;

use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Filters\AbstractFilter;

class IsActiveFilter extends AbstractFilter
{
    public function mappings(): array
    {
        return [
            'false' => false,
            'true' => true,
        ];
    }

    public function filter(Builder $query, $value): Builder
    {
        $value = $this->resolveFilterValue($value);

        if ($value === null) {
            return $query;
        }

        return $query->where('is_active', $value);
    }
}
