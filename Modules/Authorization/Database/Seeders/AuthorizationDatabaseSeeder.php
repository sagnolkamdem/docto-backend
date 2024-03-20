<?php

namespace Modules\Authorization\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Traits\DisableForeignKeys;
use Modules\Core\Traits\TruncateTable;

class AuthorizationDatabaseSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        app()['cache']->forget('spatie.permission.cache');

        $this->truncateMultiple([
            config('permission.table_names.model_has_permissions'),
            config('permission.table_names.model_has_roles'),
            config('permission.table_names.role_has_permissions'),
            config('permission.table_names.permissions'),
            config('permission.table_names.roles'),
        ]);

        $this->call([
            RoleTableSeeder::class,
            PermissionTableSeeder::class,
            PermissionRoleTableSeeder::class,
        ]);

        $this->enableForeignKeys();
    }
}
