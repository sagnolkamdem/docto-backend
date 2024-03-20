<?php

namespace Modules\Speciality\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Practician\Entities\Practician;
use Modules\Speciality\Filters\SpecialityFilters;

class Speciality extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'status',
        'slug',
        'avatar'
    ];

    protected static function boot()
    {
        parent::boot();
        static::created(function ($speciality) {
            $speciality->slug = $speciality->generateSlug($speciality->name." ".$speciality->id);
            $speciality->save();
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

    protected $casts = [
        'status' => 'boolean',
    ];

    protected static function newFactory()
    {
        return \Modules\Speciality\Database\factories\SpecialityFactory::new();
    }

    public function practicians(): HasMany
    {
        return $this->hasMany(Practician::class, 'speciality', 'id');
    }

    public function scopeFilter(Builder $query, Request $request, array $filters = []): Builder
    {
        return (new SpecialityFilters($request))->add($filters)->filter($query);
    }
}
