<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Membuat permissions
        $permissions = [
            'create-post',
            'edit-post',
            'delete-post',
            'view-post',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Membuat roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // Memberikan permissions ke role admin
        $adminRole->givePermissionTo($permissions);

        // Memberikan permissions ke role user
        $userRole->givePermissionTo(['view-post']);
    }
}
