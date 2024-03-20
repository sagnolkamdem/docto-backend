<?php

namespace Modules\Appointment\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Address\Entities\Address;
use Modules\Appointment\Enums\Status;
use Modules\Appointment\Filters\AppointmentFilters;
use Modules\Document\Entities\Document;
use Modules\Establishment\Entities\Establishment;
use Modules\Motif\Entities\Motif;
use Modules\Practician\Entities\Practician;
use Modules\TimeSlot\Entities\TimeSlot;
use Modules\User\Entities\User;
use Modules\User\Filters\UserFilters;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'practician_id',
        'establishment_id',
        'address_id',
        'time_slot',
        'status',
        'canceled_at',
        'resolved_at',
        'payload',
        'motif',
        'mode',
        'first_time',
        'canceled_by'
    ];

    protected $casts = [
        'canceled_at' => 'datetime',
        'resolved_at' => 'datetime',
        'payload' => 'json',
//        'time_slot' => 'array',
        'first_time' => 'boolean',
        'status' =>  Status::class,
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id', 'id');
    }

    public function canceler(): HasOne
    {
        return $this->hasOne(Practician::class, 'id', 'canceled_by');
    }

    public function practician(): BelongsTo
    {
        return $this->belongsTo(Practician::class);
    }

    public function transfers()
    {
        return $this->belongsToMany(
            Practician::class,
            'transfers',
            'appointment_id',
            'practician_id');
    }

    public function establishment(): BelongsTo
    {
        return $this->belongsTo(Establishment::class);
    }

    public function address(): HasOne
    {
        return $this->hasOne(Address::class, 'id', 'address_id' );
    }

    public function motif(): HasOne
    {
        return $this->hasOne(Motif::class, 'id', 'motif' );
    }

    public function timeSlot(): HasOne
    {
        return $this->hasOne(TimeSlot::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function transferedDocs()
    {
        return $this->belongsToMany(
            Document::class,
            'document_transfers',
            'appointment_id',
            'document_id');
    }

    public function scopeFilter(Builder $query, $request, array $filters = []): Builder
    {
        return (new AppointmentFilters($request))->add($filters)->filter($query);
    }
}
