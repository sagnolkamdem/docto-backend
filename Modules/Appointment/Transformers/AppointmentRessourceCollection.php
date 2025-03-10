<?php

namespace Modules\Appointment\Transformers;

use Modules\Core\Transformers\PaginationResourceCollection;

class AppointmentRessourceCollection extends PaginationResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(fn ($appointment) => new AppointmentRessource($appointment)),
            'pagination' => $this->pagination,
            'filters' => $this->filters,
        ];
    }
}
