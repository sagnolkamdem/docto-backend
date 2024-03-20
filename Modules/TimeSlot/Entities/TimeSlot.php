<?php

namespace Modules\TimeSlot\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Appointment\Entities\Appointment;
use Modules\Establishment\Entities\Establishment;
use Modules\Practician\Entities\Practician;

class TimeSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'practician_id',
        'establishment_id',
        'appointment_id',
        'description',
        'payload'
    ];

    protected $casts = [
        'payload' => 'array',
        'status' => 'boolean',
    ];
    protected $table = 'timeslots';

    public function practician(): BelongsTo
    {
        return $this->belongsTo(Practician::class);
    }

    public function establishment(): BelongsTo
    {
        return $this->belongsTo(Establishment::class);
    }
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
