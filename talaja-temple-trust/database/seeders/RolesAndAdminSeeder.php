<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndAdminSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'Super Admin' => ['admin'],
            'Trustee' => ['trustee'],
            'Admin' => ['admin'],
            'Officer' => ['staff'],
            'Staff' => ['staff'],
            'Devotee' => ['devotee'],
        ];

        foreach ($roles as $name => $types) {
            Role::firstOrCreate(['name' => $name]);
        }

        // Grant all permissions to Super Admin via gate.
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Seed core module permissions.
        $modules = [
            'donation', 'accommodation', 'finance', 'cms', 'feedback',
            'communication', 'report', 'user', 'audit', 'shop', 'setting',
        ];
        $actions = ['view', 'create', 'update', 'delete'];
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$action} {$module}"]);
            }
        }

        $superAdmin = Role::where('name', 'Super Admin')->first();
        $superAdmin->syncPermissions(Permission::all());

        // Default super admin user.
        $user = User::firstOrCreate(
            ['email' => 'admin@talajatemple.org'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
                'type' => 'admin',
                'is_active' => true,
            ]
        );
        $user->assignRole('Super Admin');
    }
}
