<?php

namespace Modules\Speciality\Filters;

use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Filters\AbstractFilter;

class StatusFilter extends AbstractFilter
{
    public function mappings(): array
    {
        return [
            'disabled' => false,
            'enabled' => true,
        ];
    }

    public function filter(Builder $query, $value): Builder
    {
        $value = $this->resolveFilterValue($value);

        if ($value === null) {
            return $query;
        }

        return $query->where('status', $value);
    }
}
