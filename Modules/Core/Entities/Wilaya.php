<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wilaya extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'code'
    ];

    protected static function newFactory()
    {
        return \Modules\Core\Database\factories\WilayaFactory::new();
    }
}
