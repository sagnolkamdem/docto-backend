<?php

namespace Modules\Antecedent\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\User\Entities\User;

class Antecedent extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id',
        'user_id',
        'description'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function type(): HasOne
    {
        return $this->hasOne(AntecedentType::class, 'id', 'type_id');
    }

    protected static function newFactory()
    {
        return \Modules\Antecedent\Database\factories\AntecedentFactory::new();
    }
}
