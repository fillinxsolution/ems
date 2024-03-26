<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role1 = Role::create(['name' => 'Super Admin',
            'guard_name' => 'api'
        ]);
        $permissions = Permission::where('guard_name', 'api')->get();
        $role1->syncPermissions($permissions);


        $role2 = Role::create(['name' => 'HR',
            'guard_name' => 'api'
        ]);
        $permissions = [
            'banks-list',
            'banks-view',
            'banks-create',
            'banks-edit',

            'account-list',
            'account-view',
            'account-create',

            'users-list',
            'users-view',
            'users-create',
            'users-edit',
            'users-delete',

            'expense-type-list',
            'expense-type-view',
            'expense-type-create',
            'expense-type-edit',

            'expense-list',
            'expense-view',
            'expense-create',

            'transfer-list',
            'transfer-view',
            'transfer-create',
            'transfer-edit',
            'transfer-delete',

            'department-list',
            'department-view',
            'department-create',
            'department-edit',
            'department-delete',


            'designation-list',
            'designation-view',
            'designation-create',
            'designation-edit',
            'designation-delete',

            'user-loan-list',
            'user-loan-view',
            'user-loan-create',

            'user-bonus-list',
            'user-bonus-view',
            'user-bonus-create',

            'wfh-list',
            'wfh-view',
            'wfh-create',
            'wfh-edit',
            'wfh-delete',

            'fine-list',
            'fine-view',
            'fine-create',
            'fine-edit',
            'fine-delete',

            'cafe-list',
            'cafe-view',
            'cafe-create',
            'cafe-edit',
            'cafe-delete',

            'cafe-expense',
            'cafe-expense-list',
            'cafe-expense-view',
            'cafe-expense-create',
            'cafe-expense-edit',
            'cafe-expense-delete',

            'salary-month',
            'salary-month-list',
            'salary-month-view',

            'salary-management',
            'salary-management-list',
            'salary-management-view',
            'salary-management-create',
            'salary-management-edit',


            'installments-list',
            'installments-view',
            'installments-create',
            'installments-edit',
            'installments-delete',


            'qualifications-list',
            'qualifications-view',
            'qualifications-create',
            'qualifications-edit',
            'qualifications-delete',

        ];
        $role2->syncPermissions($permissions);

        $role3 = Role::create(['name' => 'Developer',
            'guard_name' => 'api'
        ]);
        $permissions = [
            'banks-list', 'account-list', 'user-bonus-list',
            'wfh-list', 'fine-list', 'cafe-list', 'salary-month-list'];
        $role3->syncPermissions($permissions);


    }
}
