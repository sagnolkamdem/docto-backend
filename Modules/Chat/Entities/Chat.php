<?php

namespace Modules\Chat\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Practician\Entities\Practician;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'type', 'deleted_at'
    ];

    public function users()
    {
        return $this->belongsToMany(Practician::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

}
