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

        Role::updateOrCreate(['name' => 'Administrator']);
        $this->managerRole();
        $this->userRole();
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
        ]);
    }

    private function userRole()
    {
        $role = Role::updateOrCreate([
            'name' => 'User',
        ]);

        $role->givePermissionTo([
            //
        ]);
    }
}
