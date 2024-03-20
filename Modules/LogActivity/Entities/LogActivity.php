<?php

namespace Modules\LogActivity\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'url',
        'method',
        'ip',
        'agent',
        'user_id',
    ];

    protected static function newFactory()
    {
        return \Modules\LogActivity\Database\factories\LogActivityFactory::new();
    }
}
