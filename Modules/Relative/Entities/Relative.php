<?php

namespace Modules\Relative\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Entities\User;

class Relative extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'type',
        'gender',
        'birthdate',
        'first_name',
        'last_name',
        'place_of_birth',
        'address',
        'height',
        'weight',
        'is_patient',
        'email',
    ];

    protected $casts = [
        'is_patient' => 'boolean',
    ];

    public function patient(): BelongsTo {
        return $this->belongsTo(User::class, 'patient_id', 'id');
    }

    public function parent() {
        return $this->patient->parent();
    }

    protected static function newFactory()
    {
        return \Modules\Relative\Database\factories\RelativeFactory::new();
    }
}
