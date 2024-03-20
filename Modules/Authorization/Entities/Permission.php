<?php

namespace Modules\Authorization\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HasFactory;

    protected static function newFactory()
    {
        // return \Modules\Authorization\Database\factories\PermissionFactory::new();
    }
}
