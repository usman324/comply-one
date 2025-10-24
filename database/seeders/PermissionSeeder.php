<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $records = [
            // //stock_report
            // [
            //     'name' => 'list_stock_report',
            //     'guard_name' => 'web',
            //     'category' => 'stock report',
            //     'display_name' => 'List',
            // ],
            // lead


            //role
            [
                'name' => 'list_role',
                'guard_name' => 'web',
                'category' => 'role',
                'display_name' => 'List',
            ],
            [
                'name' => 'add_role',
                'guard_name' => 'web',
                'category' => 'role',
                'display_name' => 'Add',
            ],
            [
                'name' => 'edit_role',
                'guard_name' => 'web',
                'category' => 'role',
                'display_name' => 'Edit',
            ],
            [
                'name' => 'delete_role',
                'guard_name' => 'web',
                'category' => 'role',
                'display_name' => 'Delete',
            ],
            //user
            [
                'name' => 'list_user',
                'guard_name' => 'web',
                'category' => 'user',
                'display_name' => 'List',
            ],
            [
                'name' => 'add_user',
                'guard_name' => 'web',
                'category' => 'user',
                'display_name' => 'Add',
            ],
            [
                'name' => 'edit_user',
                'guard_name' => 'web',
                'category' => 'user',
                'display_name' => 'Edit',
            ],
            [
                'name' => 'delete_user',
                'guard_name' => 'web',
                'category' => 'user',
                'display_name' => 'Delete',
            ],
            // [
            //     'name' => 'list_user_history',
            //     'guard_name' => 'web',
            //     'category' => 'user history',
            //     'display_name' => 'View',
            // ],
            //unit
            [
                'name' => 'list_workspace',
                'guard_name' => 'web',
                'category' => 'workspaces',
                'display_name' => 'List',
            ],
            [
                'name' => 'add_workspace',
                'guard_name' => 'web',
                'category' => 'workspaces',
                'display_name' => 'Add',
            ],
            [
                'name' => 'edit_workspace',
                'guard_name' => 'web',
                'category' => 'workspaces',
                'display_name' => 'Edit',
            ],
            [
                'name' => 'delete_workspace',
                'guard_name' => 'web',
                'category' => 'workspaces',
                'display_name' => 'Delete',
            ],

        ];
        Permission::insert($records);
    }
}
