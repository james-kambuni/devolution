<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $admin=User::create([
            'name'=>'Super Admin', 
            'phone'=>'+254700000000', 
            'email'=>'admin@loan.com', 
            'avatar'=>'avatar.jpg',
            'password'=>Hash::make('loan101'), 
            'role_id'=>1
        ]);

        $admin->syncRoles(['Super admin']);
        
        $permissions=array();
        foreach($admin->role->permissions as $permission){
                    $permissions[]=$permission->name;
                }

        $admin->syncPermissions($permissions);


        $user=User::create([
            'name'=>'Test User', 
            'phone'=>'+254700000089', 
            'email'=>'user@loan.com', 
            'avatar'=>'avatar.jpg',
            'password'=>Hash::make('loan101'), 
            'role_id'=>2
        ]);

        $user->syncRoles(['Super admin']);
        
        $permissions=array();
        foreach($user->role->permissions as $permission){
                    $permissions[]=$permission->name;
                }

        $user->syncPermissions($permissions);
    }
}
