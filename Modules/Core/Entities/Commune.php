<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Filters\CommunesFilters;
use Modules\Core\Filters\CountriesFilters;

class Commune extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'code_postal',
        'wilaya_id',
    ];

    public function wilaya(): BelongsTo {
        return $this->belongsTo(Wilaya::class, 'wilaya_id', 'id');
    }

    public function scopeFilter(Builder $query, $request, array $filters = []): Builder
    {
        return (new CommunesFilters($request))->add($filters)->filter($query);
    }
}
