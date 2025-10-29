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
            // Users Management
            [
                'name' => 'list_user',
                'guard_name' => 'web',
                'category' => 'users',
                'display_name' => 'List Users',
            ],
            [
                'name' => 'add_user',
                'guard_name' => 'web',
                'category' => 'users',
                'display_name' => 'Add User',
            ],
            [
                'name' => 'edit_user',
                'guard_name' => 'web',
                'category' => 'users',
                'display_name' => 'Edit User',
            ],
            [
                'name' => 'delete_user',
                'guard_name' => 'web',
                'category' => 'users',
                'display_name' => 'Delete User',
            ],
            [
                'name' => 'assign_role',
                'guard_name' => 'web',
                'category' => 'users',
                'display_name' => 'Assign Role',
            ],


            // questionnaire
            [
                'name' => 'view_questionnaire',
                'guard_name' => 'web',
                'category' => 'questionnaires',
                'display_name' => 'View questionnaire',
            ],
            [
                'name' => 'add_questionnaire',
                'guard_name' => 'web',
                'category' => 'questionnaires',
                'display_name' => 'Create questionnaire',
            ],
            [
                'name' => 'edit_questionnaire',
                'guard_name' => 'web',
                'category' => 'questionnaires',
                'display_name' => 'Edit questionnaire',
            ],
            
            [
                'name' => 'delete_questionnaire',
                'guard_name' => 'web',
                'category' => 'questionnaires',
                'display_name' => 'Delete Questionnaire',
            ],
            // vendors
            [
                'name' => 'view_vendor',
                'guard_name' => 'web',
                'category' => 'vendors',
                'display_name' => 'View vendor',
            ],
            [
                'name' => 'add_vendor',
                'guard_name' => 'web',
                'category' => 'vendors',
                'display_name' => 'Create vendor',
            ],
            [
                'name' => 'edit_vendor',
                'guard_name' => 'web',
                'category' => 'vendors',
                'display_name' => 'Edit vendor',
            ],
            [
                'name' => 'approve_vendor',
                'guard_name' => 'web',
                'category' => 'vendors',
                'display_name' => 'Approve Vendor',
            ],
            [
                'name' => 'delete_vendor',
                'guard_name' => 'web',
                'category' => 'vendors',
                'display_name' => 'Delete Vendor',
            ],
            // policy
            [
                'name' => 'view_policy',
                'guard_name' => 'web',
                'category' => 'policies',
                'display_name' => 'View Policy',
            ],
            [
                'name' => 'add_policy',
                'guard_name' => 'web',
                'category' => 'policies',
                'display_name' => 'Create Policy',
            ],
            [
                'name' => 'edit_policy',
                'guard_name' => 'web',
                'category' => 'policies',
                'display_name' => 'Edit Policy',
            ],
            [
                'name' => 'approve_policy',
                'guard_name' => 'web',
                'category' => 'policies',
                'display_name' => 'Approve Policy',
            ],
            [
                'name' => 'delete_policy',
                'guard_name' => 'web',
                'category' => 'policies',
                'display_name' => 'Delete Policy',
            ],

          

            // Reports
            [
                'name' => 'view_report',
                'guard_name' => 'web',
                'category' => 'reports',
                'display_name' => 'View Report',
            ],
            [
                'name' => 'add_report',
                'guard_name' => 'web',
                'category' => 'reports',
                'display_name' => 'Create Report',
            ],
            [
                'name' => 'export_report',
                'guard_name' => 'web',
                'category' => 'reports',
                'display_name' => 'Export Report',
            ],

          

            // Workspaces
            [
                'name' => 'list_workspace',
                'guard_name' => 'web',
                'category' => 'workspaces',
                'display_name' => 'List Workspaces',
            ],
            [
                'name' => 'add_workspace',
                'guard_name' => 'web',
                'category' => 'workspaces',
                'display_name' => 'Add Workspace',
            ],
            [
                'name' => 'edit_workspace',
                'guard_name' => 'web',
                'category' => 'workspaces',
                'display_name' => 'Edit Workspace',
            ],
            [
                'name' => 'delete_workspace',
                'guard_name' => 'web',
                'category' => 'workspaces',
                'display_name' => 'Delete Workspace',
            ],

            // Roles
            [
                'name' => 'list_role',
                'guard_name' => 'web',
                'category' => 'roles',
                'display_name' => 'List Roles',
            ],
            [
                'name' => 'add_role',
                'guard_name' => 'web',
                'category' => 'roles',
                'display_name' => 'Add Role',
            ],
            [
                'name' => 'edit_role',
                'guard_name' => 'web',
                'category' => 'roles',
                'display_name' => 'Edit Role',
            ],
            [
                'name' => 'delete_role',
                'guard_name' => 'web',
                'category' => 'roles',
                'display_name' => 'Delete Role',
            ],
        ];

        Permission::insert($records);
    }
}
