<?php

namespace Modules\Chat\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Practician\Entities\Practician;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'chat_id',
        'body',
        'parent_id',
        'status',
        'deleted',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'deleted' => 'boolean',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(Practician::class);
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function attachments()
    {
        return $this->hasMany(MessageAttachment::class);
    }

    public function parent()
    {
        return $this->belongsTo(__CLASS__, 'parent_id', 'id');
    }
}
