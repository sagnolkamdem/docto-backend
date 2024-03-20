<?php

namespace Modules\Appointment\Filters;

use Modules\Core\Filters\AbstractFilters;

class AppointmentFilters extends AbstractFilters
{
    public array $filters = [
        'status' => StatusFilter::class,
        'practician' => PracticianFilter::class,
        'search' => SearchFilter::class,
        'period' => PeriodFilter::class,
        'patient' => PatientFilter::class
    ];
}
