<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Core\Filters\CommunesFilters;
use Modules\Core\Filters\CountriesFilters;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function scopeIsActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFilter(Builder $query, $request, array $filters = []): Builder
    {
        return (new CommunesFilters($request))->add($filters)->filter($query);
    }

    protected static function newFactory()
    {
        return \Modules\Core\Database\factories\CityFactory::new();
    }
}
