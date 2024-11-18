<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Permission::create(['name' => 'View user', 'guard_name' => 'api']);
        Permission::create(['name' => 'Create user', 'guard_name' => 'api']);
        Permission::create(['name' => 'Edit user', 'guard_name' => 'api']);
        Permission::create(['name' => 'Delete user', 'guard_name' => 'api']);

        
    }
}
