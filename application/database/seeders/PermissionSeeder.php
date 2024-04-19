<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing permissions
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('permissions')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Provide role descriptions via language translations in lang/<locale>/permissions.php
        $permissions = [
            'form_data.read',
            'form_data.update',
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
            'enquiry.read',
            'enquiry.create',
            'enquiry.update',
            'code_table.read',
            'code_table.create',
            'code_table.update',
            'code.read',
            'code.create',
            'code.update',
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
            ]);
        }
    }
}
