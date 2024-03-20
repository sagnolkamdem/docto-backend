<?php

namespace Modules\Authorization\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Authorization\Entities\Permission;
use Modules\Authorization\Entities\Role;
use Modules\Core\Traits\DisableForeignKeys;

class PermissionRoleTableSeeder extends Seeder
{
    use DisableForeignKeys;

    public function run(): void
    {
        $this->disableForeignKeys();

        $administrator = Role::query()->where('name', 'admin')->firstOrFail();
        $permissions = Permission::all();
        $administrator->permissions()->sync($permissions->pluck('id')->all());

        $this->enableForeignKeys();
    }
}
