<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class ComprehensiveUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('👥 Creating Comprehensive User System with All Roles...');

        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define users for each role
        $users = [
            // Super Admin
            [
                'name' => 'Super Administrator',
                'email' => 'superadmin@siteledger.com',
                'password' => 'SuperSecure123!',
                'role' => 'super-admin',
                'is_super_admin' => true,
                'email_verified_at' => now(),
            ],

            // System Administrator (Platform Admin with super-admin dashboard access)
            [
                'name' => 'System Administrator',
                'email' => 'sysadmin@siteledger.com',
                'password' => 'SysAdmin123!',
                'role' => 'system administrator',
                'is_super_admin' => true,
                'email_verified_at' => now(),
            ],

            // Administrators
            [
                'name' => 'Admin User',
                'email' => 'admin@siteledger.com',
                'password' => 'SecureAdmin123!',
                'role' => 'admin',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Gashumba Admin',
                'email' => 'gashumba@siteledger.com',
                'password' => 'password',
                'role' => 'admin',
                'email_verified_at' => now(),
            ],

            // Project Managers
            [
                'name' => 'Project Manager',
                'email' => 'manager@siteledger.com',
                'password' => 'SecureManager123!',
                'role' => 'manager',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Senior Project Manager',
                'email' => 'seniormanager@siteledger.com',
                'password' => 'SecureManager123!',
                'role' => 'manager',
                'email_verified_at' => now(),
            ],

            // Accountants
            [
                'name' => 'Chief Accountant',
                'email' => 'accountant@siteledger.com',
                'password' => 'SecureAccountant123!',
                'role' => 'accountant',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Financial Controller',
                'email' => 'controller@siteledger.com',
                'password' => 'SecureAccountant123!',
                'role' => 'accountant',
                'email_verified_at' => now(),
            ],

            // Employees
            [
                'name' => 'John Employee',
                'email' => 'employee@siteledger.com',
                'password' => 'SecureEmployee123!',
                'role' => 'employee',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@siteledger.com',
                'password' => 'SecureEmployee123!',
                'role' => 'employee',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Mike Johnson',
                'email' => 'mike.johnson@siteledger.com',
                'password' => 'SecureEmployee123!',
                'role' => 'employee',
                'email_verified_at' => now(),
            ],

            // Clients
            [
                'name' => 'ABC Corporation',
                'email' => 'client@abccorp.com',
                'password' => 'SecureClient123!',
                'role' => 'client',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'XYZ Limited',
                'email' => 'contact@xyzltd.com',
                'password' => 'SecureClient123!',
                'role' => 'client',
                'email_verified_at' => now(),
            ],

            // Viewers (for auditing/reporting)
            [
                'name' => 'Audit Viewer',
                'email' => 'auditor@siteledger.com',
                'password' => 'SecureViewer123!',
                'role' => 'viewer',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Report Viewer',
                'email' => 'reports@siteledger.com',
                'password' => 'SecureViewer123!',
                'role' => 'viewer',
                'email_verified_at' => now(),
            ],

            // Legacy user role (for backward compatibility)
            [
                'name' => 'Regular User',
                'email' => 'user@siteledger.com',
                'password' => 'SecureUser123!',
                'role' => 'employee', // Map old 'user' role to 'employee'
                'email_verified_at' => now(),
            ],
        ];

        // Only create test users in non-production environments
        if (!app()->environment('production')) {
            foreach ($users as $userData) {
                $user = User::firstOrCreate(
                    ['email' => $userData['email']],
                    [
                        'name' => $userData['name'],
                        'email' => $userData['email'],
                        'password' => Hash::make($userData['password']),
                        'role' => $userData['role'],
                        'is_super_admin' => $userData['is_super_admin'] ?? false,
                        'email_verified_at' => $userData['email_verified_at'],
                    ]
                );

                // Ensure role is assigned
                if (!$user->hasRole($userData['role'])) {
                    $user->assignRole($userData['role']);
                }

                $this->command->info("✅ Created: {$userData['name']} ({$userData['email']}) - Role: {$userData['role']}");
            }

            $this->command->info('');
            $this->command->info('🔐 Development Login Credentials:');
            $this->command->table(
                ['Role', 'Name', 'Email', 'Password'],
                [
                    ['Super Admin', 'Super Administrator', 'superadmin@siteledger.com', 'SuperSecure123!'],
                    ['System Admin', 'System Administrator', 'sysadmin@siteledger.com', 'SysAdmin123!'],
                    ['Admin', 'Admin User', 'admin@siteledger.com', 'SecureAdmin123!'],
                    ['Admin', 'Gashumba Admin', 'gashumba@siteledger.com', 'password'],
                    ['Manager', 'Project Manager', 'manager@siteledger.com', 'SecureManager123!'],
                    ['Manager', 'Senior Project Manager', 'seniormanager@siteledger.com', 'SecureManager123!'],
                    ['Accountant', 'Chief Accountant', 'accountant@siteledger.com', 'SecureAccountant123!'],
                    ['Accountant', 'Financial Controller', 'controller@siteledger.com', 'SecureAccountant123!'],
                    ['Employee', 'John Employee', 'employee@siteledger.com', 'SecureEmployee123!'],
                    ['Employee', 'Jane Smith', 'jane.smith@siteledger.com', 'SecureEmployee123!'],
                    ['Employee', 'Mike Johnson', 'mike.johnson@siteledger.com', 'SecureEmployee123!'],
                    ['Client', 'ABC Corporation', 'client@abccorp.com', 'SecureClient123!'],
                    ['Client', 'XYZ Limited', 'contact@xyzltd.com', 'SecureClient123!'],
                    ['Viewer', 'Audit Viewer', 'auditor@siteledger.com', 'SecureViewer123!'],
                    ['Viewer', 'Report Viewer', 'reports@siteledger.com', 'SecureViewer123!'],
                    ['Employee', 'Regular User', 'user@siteledger.com', 'SecureUser123!'],
                ]
            );

            $this->command->info('');
            $this->command->info('🛡️ Super Admin Dashboard Access:');
            $this->command->info('   - superadmin@siteledger.com (Super Admin role + is_super_admin flag)');
            $this->command->info('   - sysadmin@siteledger.com (System Administrator role + is_super_admin flag)');
            $this->command->info('   URL: /super-admin/dashboard');

            // Show role distribution
            $this->command->info('');
            $this->command->info('👥 User Distribution by Role:');
            $roleCounts = [];
            foreach ($users as $userData) {
                $roleCounts[$userData['role']] = ($roleCounts[$userData['role']] ?? 0) + 1;
            }

            $roleTable = [];
            foreach ($roleCounts as $role => $count) {
                $roleObj = Role::where('name', $role)->first();
                $roleTable[] = [
                    $role,
                    $roleObj ? $roleObj->permissions()->count() : 0,
                    $count,
                ];
            }

            $this->command->table(
                ['Role', 'Permissions', 'Users'],
                $roleTable
            );

        } else {
            $this->command->info('🔒 Production environment detected.');
            $this->command->info('Creating only essential admin users...');

            // In production, only create super admin and main admin
            $productionUsers = array_filter($users, function($user) {
                return in_array($user['email'], [
                    'superadmin@siteledger.com',
                    'admin@siteledger.com',
                    'gashumba@siteledger.com'
                ]);
            });

            foreach ($productionUsers as $userData) {
                $user = User::firstOrCreate(
                    ['email' => $userData['email']],
                    [
                        'name' => $userData['name'],
                        'email' => $userData['email'],
                        'password' => Hash::make($userData['password']),
                        'role' => $userData['role'],
                        'is_super_admin' => $userData['is_super_admin'] ?? false,
                        'email_verified_at' => $userData['email_verified_at'],
                    ]
                );

                if (!$user->hasRole($userData['role'])) {
                    $user->assignRole($userData['role']);
                }

                $this->command->info("✅ Created production user: {$userData['name']} ({$userData['role']})");
            }

            $this->command->info('🛡️  Production users created with secure credentials.');
            $this->command->info('💡 Create additional users manually through the admin panel.');
        }

        $this->command->info('');
        $this->command->info('✅ Comprehensive User System created successfully!');
        $this->command->info('📊 Total Users: ' . count($users));

        if (!app()->environment('production')) {
            $this->command->info('');
            $this->command->info('🎯 Test the enhanced sidebar with different user roles:');
            $this->command->info('1. Login as admin@siteledger.com to see ALL sections');
            $this->command->info('2. Login as manager@siteledger.com to see Project Management');
            $this->command->info('3. Login as accountant@siteledger.com to see Financial Management');
            $this->command->info('4. Login as employee@siteledger.com to see Core Features only');
        }
    }
}
