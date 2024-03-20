<?php

namespace Modules\Authorization\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Authorization\Entities\Permission;
use Modules\Core\Traits\DisableForeignKeys;

class PermissionTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        Permission::create([
            'name' => 'access_dashboard',
            'group_name' => 'system',
            'display_name' => __('Accès Dashboard'),
            'description' => __('Cette autorisation donne à l\'utilisateur l\'accès à la zone d\'administration.'),
            'can_be_removed' => false,
        ]);

        Permission::create([
            'name' => 'access_setting',
            'group_name' => 'system',
            'display_name' => __('Accès Paramètres'),
            'description' => __('Cette autorisation permet à l\'utilisateur de visualiser la page de configuration.'),
            'can_be_removed' => false,
        ]);

        Permission::create([
            'name' => 'view_users',
            'group_name' => 'system',
            'display_name' => __('Afficher liste Utilisateurs'),
            'description' => __('Cette autorisation permet à l\'utilisateur d\'accéder à l\'espace administrateur..'),
            'can_be_removed' => false,
        ]);

        $this->enableForeignKeys();
    }
}
