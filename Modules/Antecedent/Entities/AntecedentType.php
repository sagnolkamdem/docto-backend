<?php

namespace Modules\Antecedent\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AntecedentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'enabled'
    ];
    protected $casts = [
        'enabled' => 'boolean',
    ];
    protected $table = 'antecedents_types';
    protected static function newFactory()
    {
        return \Modules\Antecedent\Database\factories\AntecedentTypeFactory::new();
    }
}
