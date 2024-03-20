<?php

namespace Modules\Notes\Filters;

use Modules\Core\Filters\AbstractFilters;

class NotesFilters extends AbstractFilters
{
    public array $filters = [
        'period' => PeriodFilter::class
    ];
}
