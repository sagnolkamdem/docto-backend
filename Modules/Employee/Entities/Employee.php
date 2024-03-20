<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Employee\Filters\EmployeeFilters;
use Illuminate\Http\Request;

class Employee extends Model
{
    use HasFactory;
 
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'status',
        'password',
        'timezone',
        'language',
        'last_login_ip',
        'last_login_at',
        'address',
        'profile_photo_url',
        'created_by'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
        'status' => 'boolean',
    ];

    public function scopeFilter(Builder $query, Request $request, array $filters = []): Builder
    {
        return (new EmployeeFilters($request))->add($filters)->filter($query);
    }
}
