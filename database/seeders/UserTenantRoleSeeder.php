<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tenant;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserTenantRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates users and assigns them to tenants with roles.
     *
     * Usage: php artisan db:seed --class=UserTenantRoleSeeder
     */
    public function run(): void
    {
        $this->command->info('👥 Creating users and assigning to tenants with roles...');

        // Ensure roles exist
        $this->ensureRolesExist();

        // Get all tenants
        $tenants = Tenant::all();

        if ($tenants->isEmpty()) {
            $this->command->warn('⚠️ No tenants found. Please run SampleTenantsSeeder first.');
            return;
        }

        // Sample users to create and assign
        $usersToAssign = [
            // Users for Rwanda Construction Co.
            [
                'name' => 'Claude Niyigena',
                'email' => 'claude.niyigena@example.com',
                'tenant_domain' => 'rwanda-construction',
                'tenant_role' => 'member',
                'system_role' => 'user',
            ],
            [
                'name' => 'Diane Uwamahoro',
                'email' => 'diane.uwamahoro@example.com',
                'tenant_domain' => 'rwanda-construction',
                'tenant_role' => 'manager',
                'system_role' => 'manager',
            ],
            [
                'name' => 'Eric Mugisha',
                'email' => 'eric.mugisha@example.com',
                'tenant_domain' => 'rwanda-construction',
                'tenant_role' => 'member',
                'system_role' => 'user',
            ],

            // Users for Kigali Tech Solutions
            [
                'name' => 'Francine Iradukunda',
                'email' => 'francine.iradukunda@example.com',
                'tenant_domain' => 'kigali-tech',
                'tenant_role' => 'admin',
                'system_role' => 'admin',
            ],
            [
                'name' => 'Gilbert Hakizimana',
                'email' => 'gilbert.hakizimana@example.com',
                'tenant_domain' => 'kigali-tech',
                'tenant_role' => 'member',
                'system_role' => 'user',
            ],
            [
                'name' => 'Honorine Mutesi',
                'email' => 'honorine.mutesi@example.com',
                'tenant_domain' => 'kigali-tech',
                'tenant_role' => 'manager',
                'system_role' => 'manager',
            ],

            // Users for East Africa Manufacturing
            [
                'name' => 'Ivan Nshimiyimana',
                'email' => 'ivan.nshimiyimana@example.com',
                'tenant_domain' => 'ea-manufacturing',
                'tenant_role' => 'manager',
                'system_role' => 'manager',
            ],
            [
                'name' => 'Jacqueline Ingabire',
                'email' => 'jacqueline.ingabire@example.com',
                'tenant_domain' => 'ea-manufacturing',
                'tenant_role' => 'member',
                'system_role' => 'accountant',
            ],
            [
                'name' => 'Kevin Rutayisire',
                'email' => 'kevin.rutayisire@example.com',
                'tenant_domain' => 'ea-manufacturing',
                'tenant_role' => 'member',
                'system_role' => 'user',
            ],

            // Users for Butare Retail Group
            [
                'name' => 'Louise Mukeshimana',
                'email' => 'louise.mukeshimana@example.com',
                'tenant_domain' => 'butare-retail',
                'tenant_role' => 'manager',
                'system_role' => 'manager',
            ],
            [
                'name' => 'Martin Ndayisaba',
                'email' => 'martin.ndayisaba@example.com',
                'tenant_domain' => 'butare-retail',
                'tenant_role' => 'member',
                'system_role' => 'user',
            ],

            // Users for Northern Transport Services
            [
                'name' => 'Nicole Uwase',
                'email' => 'nicole.uwase@example.com',
                'tenant_domain' => 'northern-transport',
                'tenant_role' => 'manager',
                'system_role' => 'manager',
            ],
            [
                'name' => 'Olivier Kamanzi',
                'email' => 'olivier.kamanzi@example.com',
                'tenant_domain' => 'northern-transport',
                'tenant_role' => 'member',
                'system_role' => 'user',
            ],

            // Users for StartUp Incubator Rwanda
            [
                'name' => 'Patricia Ishimwe',
                'email' => 'patricia.ishimwe@example.com',
                'tenant_domain' => 'startup-incubator',
                'tenant_role' => 'manager',
                'system_role' => 'manager',
            ],
            [
                'name' => 'Quentin Bizimana',
                'email' => 'quentin.bizimana@example.com',
                'tenant_domain' => 'startup-incubator',
                'tenant_role' => 'member',
                'system_role' => 'user',
            ],

            // Users for Kamonyi Agricultural Coop
            [
                'name' => 'Rachel Uwineza',
                'email' => 'rachel.uwineza@example.com',
                'tenant_domain' => 'kamonyi-agri',
                'tenant_role' => 'manager',
                'system_role' => 'manager',
            ],
            [
                'name' => 'Samuel Tuyisenge',
                'email' => 'samuel.tuyisenge@example.com',
                'tenant_domain' => 'kamonyi-agri',
                'tenant_role' => 'member',
                'system_role' => 'user',
            ],
        ];

        // Create unassigned users (for testing the "Assign User to Tenant" feature)
        $unassignedUsers = [
            [
                'name' => 'Thomas Niyonzima',
                'email' => 'thomas.niyonzima@example.com',
                'system_role' => 'user',
            ],
            [
                'name' => 'Ursule Kayitesi',
                'email' => 'ursule.kayitesi@example.com',
                'system_role' => 'manager',
            ],
            [
                'name' => 'Vincent Habineza',
                'email' => 'vincent.habineza@example.com',
                'system_role' => 'accountant',
            ],
            [
                'name' => 'Winifred Muhire',
                'email' => 'winifred.muhire@example.com',
                'system_role' => 'user',
            ],
            [
                'name' => 'Xavier Ndungutse',
                'email' => 'xavier.ndungutse@example.com',
                'system_role' => 'user',
            ],
        ];

        $assignedCount = 0;
        $createdCount = 0;

        // Process users to be assigned to tenants
        foreach ($usersToAssign as $userData) {
            $tenant = Tenant::where('domain', $userData['tenant_domain'])->first();

            if (!$tenant) {
                $this->command->warn("  ⚠️ Tenant not found: {$userData['tenant_domain']}");
                continue;
            }

            $user = User::where('email', $userData['email'])->first();

            if (!$user) {
                $user = User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make('Password123!'),
                    'email_verified_at' => now(),
                    'is_super_admin' => false,
                ]);
                $createdCount++;
                $this->command->info("  ✅ Created user: {$userData['name']}");
            }

            // Assign system role
            if (Role::where('name', $userData['system_role'])->exists()) {
                $user->syncRoles([$userData['system_role']]);
            }

            // Check if user is already assigned to tenant
            if (!$user->belongsToTenant($tenant->id)) {
                $isAdmin = $userData['tenant_role'] === 'admin';
                $user->addToTenant($tenant->id, $userData['tenant_role'], $isAdmin);
                $assignedCount++;
                $this->command->info("  🔗 Assigned {$userData['name']} to {$tenant->name} as {$userData['tenant_role']}");
            } else {
                $this->command->info("  ℹ️ {$userData['name']} already assigned to {$tenant->name}");
            }
        }

        // Create unassigned users
        $unassignedCount = 0;
        $this->command->info('');
        $this->command->info('👤 Creating unassigned users (for testing tenant assignment)...');

        foreach ($unassignedUsers as $userData) {
            $user = User::where('email', $userData['email'])->first();

            if (!$user) {
                $user = User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make('Password123!'),
                    'email_verified_at' => now(),
                    'is_super_admin' => false,
                ]);
                $unassignedCount++;
                $this->command->info("  ✅ Created unassigned user: {$userData['name']}");
            }

            // Assign system role
            if (Role::where('name', $userData['system_role'])->exists()) {
                $user->syncRoles([$userData['system_role']]);
            }
        }

        $this->command->info('');
        $this->command->info('🎉 User-Tenant-Role seeding completed!');
        $this->command->info('📊 Summary:');
        $this->command->info("  • Users created: {$createdCount}");
        $this->command->info("  • Users assigned to tenants: {$assignedCount}");
        $this->command->info("  • Unassigned users created: {$unassignedCount}");
        $this->command->info('');
        $this->command->info('🔑 Default password for all users: Password123!');
        $this->command->info('');
        $this->command->info('📋 Unassigned users (for testing):');

        foreach ($unassignedUsers as $userData) {
            $this->command->info("  • {$userData['name']} ({$userData['email']})");
        }
    }

    /**
     * Ensure necessary roles exist
     */
    private function ensureRolesExist(): void
    {
        $roles = ['super-admin', 'admin', 'manager', 'accountant', 'user'];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }
    }
}
