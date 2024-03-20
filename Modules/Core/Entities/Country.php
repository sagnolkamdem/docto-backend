<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Filters\CountriesFilters;
use Modules\Operator\Entities\Operator;

class Country extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'name_official',
        'is_active',
        'is_enabled',
        'cca3',
        'cca2',
        'flag',
        'latitude',
        'longitude',
        'currencies',
        'callingCodes'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'callingCodes' => 'array',
        'currencies' => 'array',
        'is_enabled' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scopeIsEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    public function scopeIsActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFilter(Builder $query, $request, array $filters = []): Builder
    {
        return (new CountriesFilters($request))->add($filters)->filter($query);
    }

    protected static function newFactory()
    {
    }
}
