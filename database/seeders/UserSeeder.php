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
        $user = User::create([
            'name'      	=> 'Super Admin',
            'email'     	=> 'nomanbutt8322@gmail.com',
            'password'  	=> 'Noman@8322',
        ]);

        $role = Role::create(['name' => 'Super Admin','guard_name' => 'web']);

        $role->syncPermissions(Permission::all());
        $user->assignRole(1);

    }
}
