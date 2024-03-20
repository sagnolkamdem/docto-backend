<?php

namespace Modules\Chat\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MessageAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'file',
        'mime_type',
        'file_name',
        'deleted',
        'deleted_at'
    ];

    protected $attributes = [

    ];

    protected $hidden = [

    ];

    protected $casts = [
        'deleted' => 'boolean'
    ];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
