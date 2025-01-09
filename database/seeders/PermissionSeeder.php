<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Define the hierarchical permissions
        $permissions = [
            [
                'name' => 'user_management',
                'caption' => 'User Management',
                'children' => [
                    ['name' => 'create_user', 'caption' => 'Create User'],
                    ['name' => 'edit_user', 'caption' => 'Edit User'],
                    ['name' => 'delete_user', 'caption' => 'Delete User'],
                    ['name' => 'view_user', 'caption' => 'View Users'],
                    ['name' => 'reset_user_password', 'caption' => 'Reset Password'],
                ],
            ],
            [
                'name' => 'role_management',
                'caption' => 'Role Management',
                'children' => [
                    ['name' => 'create_role', 'caption' => 'Create Role'],
                    ['name' => 'edit_role', 'caption' => 'Edit Role'],
                    ['name' => 'delete_role', 'caption' => 'Delete Role'],
                    ['name' => 'view_role', 'caption' => 'View Roles'],
                ],
            ],
        ];

        // Recursively create or update permissions
        foreach ($permissions as $permission) {
            $this->updateOrCreatePermissionWithChildren($permission);
        }
    }

    private function updateOrCreatePermissionWithChildren(array $permission, $parentId = null)
    {
        // Update or create the parent permission
        $parent = Permission::updateOrCreate(
            [
                'name' => $permission['name'],
                'guard_name' => 'api',
                'caption' => $permission['caption']
            ],
            ['parent_id' => $parentId]
        );

        // Update or create child permissions if they exist
        if (isset($permission['children'])) {
            foreach ($permission['children'] as $child) {
                $this->updateOrCreatePermissionWithChildren($child, $parent->id);
            }
        }
    }
}
