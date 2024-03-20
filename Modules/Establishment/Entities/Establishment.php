<?php

namespace Modules\Establishment\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Address\Entities\Address;
use Modules\Establishment\Filters\EstablishmentFilters;
use Modules\Practician\Entities\Practician;
use Modules\TimeSlot\Entities\TimeSlot;

class Establishment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'city',
        'admin_practician',
        'address',
        'postal_code',
        'description',
        'status',
        'slug',
        'emergency',
        'head_quarter',
        'time_slots'
    ];

    protected $casts = [
        'status' => 'boolean',
        'time_slots' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();
        static::created(function ($establishment) {
            $establishment->slug = $establishment->generateSlug($establishment->name." ".$establishment->id);
            $establishment->save();
        });
    }

    private function generateSlug($name)
    {
        if (static::whereSlug($slug = Str::slug($name))->exists()) {
            $max = static::whereName($name)->latest('id')->skip(1)->value('slug');
            if (isset($max[-1]) && is_numeric($max[-1])) {
                return preg_replace_callback('/(\d+)$/', function($mathces) {
                    return $mathces[1] + 1;
                }, $max);
            }
            return "{$slug}-2";
        }
        return $slug;
    }

    public function addresss(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Practician::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Practician::class,'admin_practician');
    }

    public function scopeFilter(Builder $query, Request $request, array $filters = []): Builder
    {
        return (new EstablishmentFilters($request))->add($filters)->filter($query);
    }
}
