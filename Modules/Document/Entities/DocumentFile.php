<?php

namespace Modules\Document\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'filename',
        'path',
    ];

    protected $table = 'document_files';
}
