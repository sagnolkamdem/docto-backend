<?php

namespace Modules\Document\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Modules\Appointment\Entities\Appointment;
use Modules\Document\Filters\DocumentFilters;
use Modules\Practician\Entities\Practician;
use Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Builder;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'path',
        'patient_id',
        'appointment_id',
        'created_by',
        'created_by_practician',
        'filename',
        'metadata'
    ];

    protected $casts = [
        'created_by_practician' => 'boolean',
        'metadata' => 'array',
    ];

    public function author() {
        if ($this->created_by_practician==true || $this->created_by_practician==1) {
            return $this->hasOne(Practician::class, 'id', 'created_by');
        }
        return $this->hasOne(User::class, 'id', 'created_by');
    }
    public function authorPro() {
        return $this->hasOne(Practician::class, 'id', 'created_by');
    }

    public function patient(): HasOne {
        return $this->hasOne(User::class, 'id', 'patient_id');
    }

    public function appointment(): HasOne {
        return $this->hasOne(Appointment::class, 'id', 'appointment_id');
    }

    public function documentFiles(): HasMany {
        return $this->hasMany(DocumentFile::class);
    }

    public function transfers()
    {
        return $this->belongsToMany(
            Appointment::class,
            'transfers',
            'document_id',
            'appointment_id');
    }

    public function scopeFilter(Builder $query, Request $request, array $filters = []): Builder
    {
        return (new DocumentFilters($request))->add($filters)->filter($query);
    }
}
