<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdminRole = Role::create(['name' => 'Super admin', 'guard_name' => 'api']);

        $superAdminRole->syncPermissions([
            'View user', 'Create user', 'Edit user', 'Delete user',

        ]);


        $userRole = Role::create(['name' => 'User', 'guard_name' => 'api']);

        $userRole->syncPermissions([
            'View user',
        ]);
    }
}
