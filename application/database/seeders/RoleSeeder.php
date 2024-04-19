<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->administratorRole();
        $this->managerRole();
        $this->userRole();
        $this->developerRole();
    }

    private function developerRole()
    {
        $role = Role::updateOrCreate(['name' => 'Developer']);

        $role->givePermissionTo([
            'form_data.update',
            'form_data.read',
            'enquiry.read',
            'enquiry.create',
            'enquiry.update',
        ]);
    }

    private function administratorRole()
    {
        $role = Role::updateOrCreate(['name' => 'System Administrator']);

        $role->givePermissionTo([
            'user.create',
            'user.read',
            'user.update',
            'user.delete',
            'user.create_outside_location',
            'user.read_outside_location',
            'user.update_outside_location',
            'user.delete_outside_location',
            'role.assign.system_administrator',
            'role.assign.manager',
            'role.assign.user',
            'audit.read',
            'form_data.read',
            'enquiry.read',
            'enquiry.create',
            'enquiry.update',
            'code_table.read',
            'code_table.create',
            'code_table.update',
            'code.read',
            'code.create',
            'code.update',
        ]);
    }

    private function managerRole()
    {
        $role = Role::updateOrCreate(['name' => 'Manager']);

        $role->givePermissionTo([
            'user.create',
            'user.read',
            'user.update',
            'user.delete',
            'role.assign.manager',
            'role.assign.user',
            'form_data.read',
            'enquiry.read',
            'enquiry.create',
            'code_table.read',
            'code.read',
            'enquiry.update',
        ]);
    }

    private function userRole()
    {
        $role = Role::updateOrCreate([
            'name' => 'User',
        ]);

        $role->givePermissionTo([
            'form_data.read',
            'enquiry.read',
            'enquiry.create',
            'code_table.read',
            'code.read',
            'enquiry.update',
        ]);
    }
}
