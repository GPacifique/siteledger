<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ComprehensiveRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔐 Creating Comprehensive Role & Permission System...');

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all permissions for the accounting system
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

            // Tenant Management (Multi-tenant)
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

        $this->command->info("Creating {" . count($permissions) . "} permissions...");

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $this->command->info('✅ Permissions created successfully!');

        // Define comprehensive roles with their specific permissions
        $roles = [
            'super-admin' => [
                'name' => 'super-admin',
                'display_name' => 'Super Administrator',
                'description' => 'System owner with complete access to all features including multi-tenant management',
                'permissions' => $permissions, // Super Admin gets ALL permissions
            ],

            'system administrator' => [
                'name' => 'system administrator',
                'display_name' => 'System Administrator',
                'description' => 'Platform-level administrator with access to super-admin dashboard and all system management features',
                'permissions' => $permissions, // System Administrator gets ALL permissions
            ],

            'admin' => [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Full access to all business features within their tenant',
                'permissions' => array_filter($permissions, function($permission) {
                    // Admin gets all permissions except super-admin specific ones
                    return !in_array($permission, [
                        'tenants.view',
                        'tenants.create',
                        'tenants.edit',
                        'tenants.delete',
                        'tenants.manage',
                    ]);
                }),
            ],

            'manager' => [
                'name' => 'manager',
                'display_name' => 'Project Manager',
                'description' => 'Manages projects, workers, employees, and orders',
                'permissions' => [
                    'dashboard.view',

                    // Core Features
                    'clients.view',
                    'reports.view',
                    'reports.generate',
                    'transactions.view',
                    'products.view',
                    'tasks.view',
                    'tasks.create',
                    'tasks.edit',
                    'tasks.assign',
                    'tasks.complete',

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

                    // Order Management
                    'orders.view',
                    'orders.create',
                    'orders.edit',
                    'orders.delete',
                    'orders.fulfill',

                    // Profile
                    'profile.view',
                    'profile.edit',

                    // Basic exports
                    'data.export',
                ],
            ],

            'accountant' => [
                'name' => 'accountant',
                'display_name' => 'Accountant',
                'description' => 'Manages financial records, payments, and financial reporting',
                'permissions' => [
                    'dashboard.view',

                    // Core Features (view only)
                    'clients.view',
                    'reports.view',
                    'reports.generate',
                    'reports.export',
                    'transactions.view',
                    'transactions.create',
                    'transactions.edit',
                    'transactions.reconcile',
                    'products.view',
                    'tasks.view',

                    // Financial Management (full access)
                    'incomes.view',
                    'incomes.create',
                    'incomes.edit',
                    'incomes.delete',
                    'incomes.export',
                    'incomes.approve',

                    'expenses.view',
                    'expenses.create',
                    'expenses.edit',
                    'expenses.delete',
                    'expenses.export',
                    'expenses.approve',

                    'payments.view',
                    'payments.create',
                    'payments.edit',
                    'payments.delete',
                    'payments.process',
                    'payments.approve',
                    'payments.export',

                    'finance.view',
                    'finance.overview',
                    'finance.reports',
                    'finance.analytics',

                    // Limited project access
                    'projects.view',

                    // Profile
                    'profile.view',
                    'profile.edit',

                    // Financial exports
                    'data.export',
                ],
            ],

            'employee' => [
                'name' => 'employee',
                'display_name' => 'Employee',
                'description' => 'Basic access to core features and personal tasks',
                'permissions' => [
                    'dashboard.view',

                    // Core Features (limited access)
                    'clients.view',
                    'reports.view',
                    'transactions.view',
                    'products.view',
                    'tasks.view',
                    'tasks.edit', // Can edit their own tasks
                    'tasks.complete',

                    // Profile management
                    'profile.view',
                    'profile.edit',

                    // Notifications
                    'notifications.view',
                ],
            ],

            'client' => [
                'name' => 'client',
                'display_name' => 'Client',
                'description' => 'Limited access for external clients to view their projects and invoices',
                'permissions' => [
                    'dashboard.view',

                    // Limited project view (only their own)
                    'projects.view',

                    // View their own transactions/invoices
                    'transactions.view',
                    'incomes.view',

                    // Profile management
                    'profile.view',
                    'profile.edit',

                    // Notifications
                    'notifications.view',
                ],
            ],

            'viewer' => [
                'name' => 'viewer',
                'display_name' => 'Viewer',
                'description' => 'Read-only access to most features for auditing or reporting purposes',
                'permissions' => [
                    'dashboard.view',

                    // View-only access to most features
                    'clients.view',
                    'projects.view',
                    'workers.view',
                    'employees.view',
                    'orders.view',
                    'products.view',
                    'tasks.view',
                    'incomes.view',
                    'expenses.view',
                    'payments.view',
                    'finance.view',
                    'reports.view',
                    'transactions.view',

                    // Profile
                    'profile.view',
                    'profile.edit',

                    // Export capabilities
                    'data.export',
                ],
            ],
        ];

        $this->command->info('Creating roles and assigning permissions...');

        foreach ($roles as $roleKey => $roleData) {
            $role = Role::firstOrCreate(
                ['name' => $roleData['name']],
                [
                    'name' => $roleData['name'],
                    'guard_name' => 'web',
                ]
            );

            // Sync permissions for this role
            $role->syncPermissions($roleData['permissions']);

            $permissionCount = count($roleData['permissions']);
            $this->command->info("✅ {$roleData['display_name']}: {$permissionCount} permissions assigned");
        }

        $this->command->info('');
        $this->command->info('🎯 Role Summary:');
        $this->command->table(
            ['Role', 'Display Name', 'Description', 'Permissions'],
            array_map(function($roleData) {
                return [
                    $roleData['name'],
                    $roleData['display_name'],
                    $roleData['description'],
                    count($roleData['permissions'])
                ];
            }, $roles)
        );

        $this->command->info('✅ Comprehensive Role & Permission System created successfully!');
        $this->command->info('📊 Total Permissions: ' . count($permissions));
        $this->command->info('👥 Total Roles: ' . count($roles));
    }
}
