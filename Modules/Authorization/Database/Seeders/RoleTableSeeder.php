<?php

namespace Modules\Authorization\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Authorization\Entities\Role;
use Modules\Core\Traits\DisableForeignKeys;

class RoleTableSeeder extends Seeder
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

        Role::create([
            'name' => 'root',
            'display_name' => 'Super Administrateur',
            'description' => __('Administrateur de la plateforme avec accès au panneau d\'administration aux configurations et aux outils de développement.'),
            'can_be_removed' => false,
        ]);

        Role::create([
            'name' => 'admin',
            'display_name' => 'Administrateur',
            'description' => __('Administrateur de la plateforme avec accès au panneau d\'administration aux configurations et aux outils de développement.'),
            'can_be_removed' => false,
        ]);

        Role::create([
            'name' => 'manager',
            'display_name' => 'Manager',
            'description' => __('Top management de la plateforme.'),
            'can_be_removed' => false,
        ]);

        Role::create([
            'name' => 'secretary_employee',
            'display_name' => 'Secretaire',
            'description' => __('Top management de la plateforme.'),
            'can_be_removed' => false,
        ]);

        Role::create([
            'name' => 'patient',
            'display_name' => 'Patient',
            'description' => __('Represente un patient sur de la plateforme.'),
            'can_be_removed' => false,
        ]);

        Role::create([
            'name' => 'practician',
            'display_name' => 'Practician',
            'description' => __('Represente un medecin sur de la plateforme.'),
            'can_be_removed' => false,
            'guard_name' => 'pro'
        ]);

        Role::create([
            'name' => 'secretary_medical',
            'display_name' => 'Secretaire',
            'description' => __('Gestionnaires de certains points sur la plateforme.'),
            'can_be_removed' => false,
            'guard_name' => 'pro'
        ]);

        Role::create([
            'name' => 'nurse',
            'display_name' => 'Infirmier',
            'description' => __('Gestionnaires de certains points sur la plateforme.'),
            'can_be_removed' => false,
            'guard_name' => 'pro'
        ]);

        Role::create([
            'name' => 'laboratory',
            'display_name' => 'Laborantin',
            'description' => __('Gestionnaires de certains points sur la plateforme.'),
            'can_be_removed' => false,
            'guard_name' => 'pro'
        ]);

        Role::create([
            'name' => 'substitute',
            'display_name' => 'Remplaçant',
            'description' => __('Gestionnaires de certains points sur la plateforme.'),
            'can_be_removed' => false,
            'guard_name' => 'pro'
        ]);

        $this->enableForeignKeys();
    }
}
