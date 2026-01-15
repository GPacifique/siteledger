<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('🔐 Creating/updating roles...');

        // Define all roles
        $roles = [
            'system administrator',
            'super-admin',
            'admin',
            'manager',
            'accountant',
            'secretary',
            'foreman',
            'site manager',
            'store keeper',
            'employee',
            'client',
            'user',
            'viewer',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(
                ['name' => $roleName, 'guard_name' => 'web']
            );
            $this->command->info("  ✅ Role '{$roleName}' ready");
        }

        $this->command->info('🎉 Roles seeding completed!');
        $this->command->info('📊 Total roles: ' . count($roles));
    }
}
