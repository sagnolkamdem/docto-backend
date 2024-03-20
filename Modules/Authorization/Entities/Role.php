<?php

namespace Modules\Authorization\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Modules\Speciality\Filters\SpecialityFilters;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory;

    public function isAdmin(): bool
    {
        return $this->name === 'admin';
    }

    public function scopeFilter(Builder $query, Request $request, array $filters = []): Builder
    {
        return (new SpecialityFilters($request))->add($filters)->filter($query);
    }
}
