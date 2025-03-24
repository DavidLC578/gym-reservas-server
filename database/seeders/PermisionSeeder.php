<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Roles
        $godRole = Role::create([
            'name' => 'god'
        ]);

        $adminRole = Role::create([
            'name' => 'admin'
        ]);

        $userRole = Role::create([
            'name' => 'user'
        ]);

        // Permissions
        $manageClasses = Permission::create(['name' => 'manage-classes'])->syncRoles($godRole, $adminRole);
        $manageUsers = Permission::create(['name' => 'manage-users'])->syncRoles($godRole, $adminRole);
    }
}
