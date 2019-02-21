<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'use administration']);
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'manage lessons']);
        Permission::create(['name' => 'manage workplaces']);
        Permission::create(['name' => 'manage announcements']);

        // create roles and assign created permissions

        $role = Role::create(['name' => 'Admin']);

        $role = Role::create(['name' => 'Registrerad']);

        $role = Role::create(['name' => 'Arbetsplatsadministratör']);
        $role->givePermissionTo('manage users');
        $role->givePermissionTo('manage workplaces');
        $role->givePermissionTo('use administration');
    }
}
