<?php

namespace Database\Seeders;

// use App\Models\Role;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'admin',
                'guard_name' => 'web',
                'is_hidden' => 1,
            ],
            [
                'name' => 'founder ceo',
                'guard_name' => 'web',
                 'is_hidden' => 0,
            ],
            [
                'name' => 'cco',
                'guard_name' => 'web',
                 'is_hidden' => 0,
            ],
            [
                'name' => 'compliance analyst',
                'guard_name' => 'web',
                 'is_hidden' => 0,
            ],
            [
                'name' => 'auditor',
                'guard_name' => 'web',
                 'is_hidden' => 0,
            ],

        ];
        Role::insert($data);
        $rolePermissions = [
            'founder ceo' => [
                // Manage users, billing, and settings
                'list_user',
                'add_user',
                'edit_user',
                'delete_user',
                'assign_role',



                // Questionnaire - Full Access
                'view_questionnaire',
                

                // Vendors - Full Access
                'view_vendor',
                'add_vendor',
                'edit_vendor',
                'approve_vendor',
                'delete_vendor',

                // Policies - Full Access
                'view_policy',
                'add_policy',
                'edit_policy',
                'approve_policy',
                'delete_policy',



                // Reports - Full Access
                'view_report',
                'add_report',
                'export_report',


                // Workspace & Role Management
                // 'list_workspace',
                // 'add_workspace',
                // 'edit_workspace',
                // 'delete_workspace',
                'list_role',
                'add_role',
                'edit_role',
                'delete_role',
            ],

            'cco' => [
                // Manage users and assign roles
                'list_user',
                'add_user',
                'edit_user',
                'assign_role',

                // Questionnaire - Create and Edit
                'view_questionnaire',

                // Vendors - Manage (no delete)
                'view_vendor',
                'add_vendor',
                'edit_vendor',
                'approve_vendor',

                // Policies - Create, Edit, and Approve
                'view_policy',
                'add_policy',
                'edit_policy',
                'approve_policy',


                // Reports - Full Access
                'view_report',
                'add_report',
                'export_report',


            ],

            'compliance analyst' => [
                // View users
                'list_user',

                // Questionnaire - View and Edit
                'view_questionnaire',

                // Vendors - View and Edit
                'view_vendor',
                'edit_vendor',

                // Policies - View only
                'view_policy',


                // Reports - View only
                'view_report',
            ],

            'auditor' => [
                // Questionnaire - View only
                'view_questionnaire',

                // Vendors - View only
                'view_vendor',

                // Policies - View only
                'view_policy',


                // Reports - View only
                'view_report',
            ],
        ];

        // Assign permissions to roles
        foreach ($rolePermissions as $roleName => $permissions) {
            $role = Role::where('name', $roleName)->first();

            if ($role) {
                $permissionIds = Permission::whereIn('name', $permissions)->pluck('id')->toArray();
                $role->syncPermissions($permissionIds);
            }
        }
    }
}
