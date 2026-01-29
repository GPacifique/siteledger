<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SeedTenantsAndLinkToUsers extends Seeder
{
    public function run()
    {
        // Get all users
        $users = User::all();
        foreach ($users as $user) {
            // Create a tenant for each user if not exists
            $tenant = Tenant::firstOrCreate(
                ['name' => $user->name ?: ($user->first_name . ' ' . $user->last_name)],
                [
                    'domain' => strtolower(str_replace(' ', '', $user->name ?: $user->first_name)) . '.example.com',
                    'email' => $user->email,
                    'status' => 'active',
                ]
            );
            // Link user to tenant in pivot table
            DB::table('tenant_users')->updateOrInsert(
                [
                    'tenant_id' => $tenant->id,
                    'user_id' => $user->id,
                ],
                [
                    'role' => 'admin',
                    'is_admin' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
            // Set user's current_tenant_id if not set
            if (!$user->current_tenant_id) {
                $user->current_tenant_id = $tenant->id;
                $user->save();
            }
        }
    }
}
