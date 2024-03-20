<?php

namespace Modules\Notes\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Modules\Document\Filters\DocumentFilters;
use Modules\Notes\Filters\NotesFilters;
use Modules\Practician\Entities\Practician;
use Modules\User\Entities\User;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'patient_id',
        'created_by',
        'created_by_practician'
    ];

    protected $casts = [
        'created_by_practician' => 'boolean'
    ];

    public function author() {
        if ($this->created_by_practician == true || $this->created_by_practician == 1) {
            return $this->hasOne(Practician::class, 'id', 'created_by');
        }
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function patient(): HasOne {
        return $this->hasOne(User::class, 'id', 'patient_id');
    }

    public function scopeFilter(Builder $query, Request $request, array $filters = []): Builder
    {
        return (new NotesFilters($request))->add($filters)->filter($query);
    }
}
