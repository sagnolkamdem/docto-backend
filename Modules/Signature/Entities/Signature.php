<?php

namespace Modules\Signature\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Practician\Entities\Practician;

class Signature extends Model
{
    use HasFactory;

    protected $fillable = [
        'practician_id',
        'filename',
        'path',
    ];

    public function practician() {
        return $this->belongsTo(Practician::class);
    }
}
