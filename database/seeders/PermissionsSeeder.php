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
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'api']);
        }
    }
}
