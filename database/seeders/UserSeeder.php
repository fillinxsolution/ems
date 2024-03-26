<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1 = User::create([
            'name'          => 'Super Admin',
            'email'         => 'nomanbutt8322@gmail.com',
            'password'      => '123456789',
            'is_admin'      => 1,
        ]);
        $role1 = Role::where('name','Super Admin')->where('guard_name','api')->first();
        $user1->assignRole($role1);

        $user2 = User::create([
            'name'          => 'HR',
            'email'         => 'hr@gmail.com',
            'password'      => '123456789',
            'is_admin'      => 1,
        ]);
        $role2 = Role::where('name','HR')->where('guard_name','api')->first();
        $user2->assignRole($role2);

        $user3 = User::create([
            'name'          => 'Developer',
            'email'         => 'developer@gmail.com',
            'password'      => '123456789',
            'is_admin'      => 0,
        ]);
        $role3 = Role::where('name','Developer')->where('guard_name','api')->first();
        $user3->assignRole($role3);

    }
}
