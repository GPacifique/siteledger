<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates the super-admin role with ALL permissions and a super admin user.
     *
     * Usage: php artisan db:seed --class=SuperAdminSeeder
     */
    public function run(): void
    {
        $this->command->info('👑 Creating Super Admin Role & User...');

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all permissions for the super admin
        $permissions = [
            // Dashboard Access
            'dashboard.view',

            // User Management
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.assign-roles',

            // Role & Permission Management
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'permissions.view',
            'permissions.create',
            'permissions.edit',
            'permissions.delete',

            // Client Management
            'clients.view',
            'clients.create',
            'clients.edit',
            'clients.delete',
            'clients.export',

            // Project Management
            'projects.view',
            'projects.create',
            'projects.edit',
            'projects.delete',
            'projects.export',
            'projects.assign-workers',

            // Worker Management
            'workers.view',
            'workers.create',
            'workers.edit',
            'workers.delete',
            'workers.export',
            'workers.payments',

            // Employee Management
            'employees.view',
            'employees.create',
            'employees.edit',
            'employees.delete',
            'employees.export',
            'employees.payroll',

            // Order Management
            'orders.view',
            'orders.create',
            'orders.edit',
            'orders.delete',
            'orders.fulfill',
            'orders.cancel',

            // Product Management
            'products.view',
            'products.create',
            'products.edit',
            'products.delete',
            'products.export',
            'products.inventory',

            // Task Management
            'tasks.view',
            'tasks.create',
            'tasks.edit',
            'tasks.delete',
            'tasks.assign',
            'tasks.complete',

            // Income Management
            'incomes.view',
            'incomes.create',
            'incomes.edit',
            'incomes.delete',
            'incomes.export',
            'incomes.approve',

            // Expense Management
            'expenses.view',
            'expenses.create',
            'expenses.edit',
            'expenses.delete',
            'expenses.export',
            'expenses.approve',

            // Payment Management
            'payments.view',
            'payments.create',
            'payments.edit',
            'payments.delete',
            'payments.process',
            'payments.approve',
            'payments.export',

            // Financial Management
            'finance.view',
            'finance.overview',
            'finance.reports',
            'finance.analytics',

            // Report Management
            'reports.view',
            'reports.create',
            'reports.edit',
            'reports.delete',
            'reports.generate',
            'reports.export',
            'reports.schedule',

            // Transaction Management
            'transactions.view',
            'transactions.create',
            'transactions.edit',
            'transactions.delete',
            'transactions.export',
            'transactions.reconcile',

            // Settings Management
            'settings.view',
            'settings.edit',
            'settings.system',
            'settings.email',
            'settings.backup',

            // Tenant Management (Multi-tenant) - Super Admin Only
            'tenants.view',
            'tenants.create',
            'tenants.edit',
            'tenants.delete',
            'tenants.manage',

            // Audit & Logs
            'audits.view',
            'audits.export',
            'logs.view',
            'logs.export',

            // Notifications
            'notifications.view',
            'notifications.create',
            'notifications.send',
            'notifications.manage',

            // Profile Management
            'profile.view',
            'profile.edit',
            'profile.delete',

            // Import/Export
            'data.import',
            'data.export',
            'data.backup',
            'data.restore',

            // Advanced Features
            'advanced.api-access',
            'advanced.integrations',
            'advanced.webhooks',
            'advanced.custom-fields',
        ];

        // Create all permissions
        $this->command->info("📝 Creating {" . count($permissions) . "} permissions...");

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $this->command->info('✅ Permissions created successfully!');

        // Create Super Admin Role
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'super-admin', 'guard_name' => 'web']
        );

        // Create System Administrator Role (required for super-admin routes)
        $systemAdminRole = Role::firstOrCreate(
            ['name' => 'system administrator', 'guard_name' => 'web']
        );

        // Assign ALL permissions to both roles
        $superAdminRole->syncPermissions($permissions);
        $systemAdminRole->syncPermissions($permissions);

        $this->command->info('✅ Super Admin & System Administrator roles created with ALL permissions!');

        // Create Super Admin User
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@siteledger.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('SuperSecure123!'),
                'role' => 'super-admin',
                'is_super_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // Ensure super admin flags are set
        if (!$superAdmin->is_super_admin) {
            $superAdmin->update(['is_super_admin' => true]);
        }

        // Assign BOTH roles - super-admin AND system administrator (for route access)
        $superAdmin->syncRoles(['super-admin', 'system administrator']);

        $this->command->info('✅ Super Admin user created successfully!');

        // Summary
        $this->command->newLine();
        $this->command->info('╔══════════════════════════════════════════════════════════════╗');
        $this->command->info('║              👑 SUPER ADMIN CREATED SUCCESSFULLY             ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║  Email:     superadmin@siteledger.com                        ║');
        $this->command->info('║  Password:  SuperSecure123!                                  ║');
        $this->command->info('║  Roles:     super-admin, system administrator                ║');
        $this->command->info('║  Access:    FULL SYSTEM ACCESS (ALL PERMISSIONS)            ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║  Features:                                                   ║');
        $this->command->info('║  ✓ Complete system access                                    ║');
        $this->command->info('║  ✓ Tenant management                                         ║');
        $this->command->info('║  ✓ User & role management                                    ║');
        $this->command->info('║  ✓ All financial features                                    ║');
        $this->command->info('║  ✓ System settings & configuration                          ║');
        $this->command->info('║  ✓ Audit logs & reporting                                    ║');
        $this->command->info('╚══════════════════════════════════════════════════════════════╝');
        $this->command->newLine();

        // Security warning for production
        if (app()->environment('production')) {
            $this->command->warn('⚠️  PRODUCTION WARNING: Change the default password immediately!');
            $this->command->warn('   Run: php artisan tinker');
            $this->command->warn('   User::where("email", "superadmin@siteledger.com")->first()->update(["password" => Hash::make("YOUR_SECURE_PASSWORD")])');
        }
    }
}
