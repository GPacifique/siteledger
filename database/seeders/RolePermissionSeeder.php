<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('🔐 Setting up role permissions...');

        // Define all permissions
        $permissions = [
            // Dashboard
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

            // Project Management
            'projects.view',
            'projects.create',
            'projects.edit',
            'projects.delete',
            'projects.export',
            'projects.assign-workers',

            // Client Management
            'clients.view',
            'clients.create',
            'clients.edit',
            'clients.delete',
            'clients.export',

            // Expense Management
            'expenses.view',
            'expenses.create',
            'expenses.edit',
            'expenses.delete',
            'expenses.approve',
            'expenses.export',

            // Income Management
            'incomes.view',
            'incomes.create',
            'incomes.edit',
            'incomes.delete',
            'incomes.export',

            // Payment Management
            'payments.view',
            'payments.create',
            'payments.edit',
            'payments.delete',
            'payments.approve',
            'payments.process',
            'payments.export',

            // Report Management
            'reports.view',
            'reports.create',
            'reports.generate',
            'reports.export',
            'reports.schedule',

            // Employee Management
            'employees.view',
            'employees.create',
            'employees.edit',
            'employees.delete',
            'employees.export',
            'employees.payroll',

            // Worker Management
            'workers.view',
            'workers.create',
            'workers.edit',
            'workers.delete',
            'workers.export',
            'workers.payments',

            // Task Management
            'tasks.view',
            'tasks.create',
            'tasks.edit',
            'tasks.delete',
            'tasks.assign',
            'tasks.complete',

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

            // Transaction Management
            'transactions.view',
            'transactions.create',
            'transactions.edit',
            'transactions.delete',
            'transactions.export',
            'transactions.reconcile',

            // Settings
            'settings.view',
            'settings.edit',
            'settings.system',
            'settings.backup',
            'settings.email',

            // Tenant Management (super admin only)
            'tenants.view',
            'tenants.create',
            'tenants.edit',
            'tenants.delete',
            'tenants.manage',

            // Profile
            'profile.view',
            'profile.edit',
            'profile.delete',

            // Logs & Audit
            'logs.view',
            'logs.export',

            // Notifications
            'notifications.view',
            'notifications.create',
            'notifications.manage',
            'notifications.send',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $this->command->info('  ✅ ' . count($permissions) . ' permissions created');

        // Define role permissions matrix
        $rolePermissions = [
            // System Administrator - Full platform access
            'system administrator' => $permissions,

            // Super Admin - Full access
            'super-admin' => $permissions,

            // Admin - Full tenant access (no tenant management)
            'admin' => array_filter($permissions, fn($p) => !str_starts_with($p, 'tenants.')),

            // Manager - Operational management
            'manager' => [
                'dashboard.view',
                'projects.view', 'projects.create', 'projects.edit', 'projects.export', 'projects.assign-workers',
                'clients.view', 'clients.create', 'clients.edit',
                'employees.view', 'employees.create', 'employees.edit',
                'workers.view', 'workers.create', 'workers.edit', 'workers.payments',
                'tasks.view', 'tasks.create', 'tasks.edit', 'tasks.assign', 'tasks.complete',
                'orders.view', 'orders.create', 'orders.edit', 'orders.fulfill',
                'reports.view', 'reports.generate',
                'transactions.view',
                'profile.view', 'profile.edit',
                'notifications.view',
            ],

            // Accountant - Financial access
            'accountant' => [
                'dashboard.view',
                'payments.view', 'payments.create', 'payments.edit', 'payments.approve', 'payments.process', 'payments.export',
                'incomes.view', 'incomes.create', 'incomes.edit', 'incomes.export',
                'expenses.view', 'expenses.create', 'expenses.edit', 'expenses.approve', 'expenses.export',
                'transactions.view', 'transactions.create', 'transactions.edit', 'transactions.export', 'transactions.reconcile',
                'reports.view', 'reports.generate', 'reports.export',
                'projects.view',
                'clients.view',
                'employees.view', 'employees.payroll',
                'workers.view', 'workers.payments',
                'profile.view', 'profile.edit',
                'notifications.view',
            ],

            // Secretary - Administrative support
            'secretary' => [
                'dashboard.view',
                'clients.view', 'clients.create', 'clients.edit',
                'projects.view',
                'employees.view',
                'workers.view',
                'tasks.view', 'tasks.create', 'tasks.edit',
                'orders.view', 'orders.create', 'orders.edit',
                'reports.view',
                'notifications.view', 'notifications.create',
                'profile.view', 'profile.edit',
            ],

            // Foreman - Site supervision and worker management
            'foreman' => [
                'dashboard.view',
                'projects.view',
                'workers.view', 'workers.create', 'workers.edit', 'workers.payments',
                'tasks.view', 'tasks.create', 'tasks.edit', 'tasks.assign', 'tasks.complete',
                'expenses.view', 'expenses.create',
                'reports.view',
                'profile.view', 'profile.edit',
                'notifications.view',
            ],

            // Site Manager - On-site management
            'site manager' => [
                'dashboard.view',
                'projects.view', 'projects.edit',
                'workers.view', 'workers.create', 'workers.edit', 'workers.payments',
                'tasks.view', 'tasks.create', 'tasks.edit', 'tasks.assign', 'tasks.complete',
                'expenses.view', 'expenses.create',
                'reports.view',
                'profile.view', 'profile.edit',
                'notifications.view',
            ],

            // Store Keeper - Inventory management
            'store keeper' => [
                'dashboard.view',
                'products.view', 'products.create', 'products.edit', 'products.inventory',
                'orders.view', 'orders.create', 'orders.edit', 'orders.fulfill',
                'reports.view',
                'profile.view', 'profile.edit',
                'notifications.view',
            ],

            // Employee - Basic access
            'employee' => [
                'dashboard.view',
                'tasks.view', 'tasks.complete',
                'projects.view',
                'profile.view', 'profile.edit',
                'notifications.view',
            ],

            // Client - External view access
            'client' => [
                'projects.view',
                'tasks.view',
                'reports.view',
                'profile.view', 'profile.edit',
            ],

            // User - Minimal access
            'user' => [
                'dashboard.view',
                'profile.view', 'profile.edit',
                'notifications.view',
            ],

            // Viewer - Read-only
            'viewer' => [
                'dashboard.view',
                'projects.view',
                'clients.view',
                'tasks.view',
                'reports.view',
                'products.view',
                'transactions.view',
                'profile.view',
            ],
        ];

        foreach ($rolePermissions as $roleName => $perms) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->syncPermissions($perms);
                $this->command->info("  ✅ {$roleName}: " . count($perms) . " permissions assigned");
            }
        }

        $this->command->info('🎉 Role permissions setup completed!');
    }
}
