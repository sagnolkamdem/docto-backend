<?php

namespace Modules\Address\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Entities\Commune;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'practician_id',
        'establishment_id',
        'description',
        'commune_id',
        'address_lines'
    ];

    protected $casts = [
        'address_lines' => 'array',
        'status' => 'boolean',
    ];

    public function commune(): BelongsTo
    {
        return $this->belongsTo(Commune::class);
    }
}
