<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'banks-list',
            'banks-view',
            'banks-create',
            'banks-edit',
            'banks-delete',

            'account-list',
            'account-view',
            'account-create',
            'account-edit',
            'account-delete',

            'users-list',
            'users-view',
            'users-create',
            'users-edit',
            'users-delete',

            'expense-type-list',
            'expense-type-view',
            'expense-type-create',
            'expense-type-edit',
            'expense-type-delete',

            'expense-list',
            'expense-view',
            'expense-create',
            'expense-edit',
            'expense-delete',

            'roles-list',
            'roles-view',
            'roles-create',
            'roles-edit',
            'roles-delete',

            'permission-list',
            'permission-view',
            'permission-create',
            'permission-edit',
            'permission-delete',

            'transfer-list',
            'transfer-view',
            'transfer-create',
            'transfer-edit',
            'transfer-delete',

            'settings-list',
            'settings-create',

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
            'user-loan-edit',
            'user-loan-delete',

            'user-bonus-list',
            'user-bonus-view',
            'user-bonus-create',
            'user-bonus-edit',
            'user-bonus-delete',

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
            'salary-month-create',
            'salary-month-edit',
            'salary-month-delete',

            'salary-management',
            'salary-management-list',
            'salary-management-view',
            'salary-management-create',
            'salary-management-edit',
            'salary-management-delete',


            'installments-list',
            'installments-view',
            'installments-create',
            'installments-edit',
            'installments-delete',


            'transaction-list',
            'transaction-view',
            'transaction-create',
            'transaction-edit',
            'transaction-delete',
            'all-transaction-list',


            'qualifications-list',
            'qualifications-view',
            'qualifications-create',
            'qualifications-edit',
            'qualifications-delete',

        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'api']);
        }
    }
}
