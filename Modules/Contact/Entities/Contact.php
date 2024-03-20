<?php

namespace Modules\Contact\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Appointment\Enums\Status;
use Modules\Appointment\Filters\AppointmentFilters;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'message',
        'status',
    ];

    protected $casts = [
        'status' => Status::class
    ];

    public function scopeFilter(Builder $query, $request, array $filters = []): Builder
    {
        return (new AppointmentFilters($request))->add($filters)->filter($query);
    }
}
