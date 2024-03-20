<?php

namespace Modules\Motif\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Practician\Entities\Practician;

class Motif extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'enabled',
        'practician_id'
    ];
    protected $casts = [
        'enabled' => 'boolean',
    ];

    protected static function newFactory()
    {
        return \Modules\Motif\Database\factories\MotifFactory::new();
    }

    public function practician(): BelongsTo
    {
        return $this->belongsTo(Practician::class);
    }
}
