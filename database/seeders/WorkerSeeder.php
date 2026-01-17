<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Worker;
use App\Models\Tenant;

class WorkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first tenant
        $tenant = Tenant::first();

        if (!$tenant) {
            $this->command->warn('No tenant found. Please ensure tenants are created first.');
            return;
        }

        $workers = [
            [
                'first_name' => 'Jean Baptiste',
                'last_name' => 'Ndayisenga',
                'email' => 'jean.baptiste@worker.rw',
                'phone' => '+250788111222',
                'position' => 'Site Supervisor',
                'daily_wage' => 500000, // 500,000 RWF
                'currency' => 'RWF',
                'status' => 'active',
                'hired_at' => now()->subMonths(14),
            ],
            [
                'first_name' => 'Emmanuel',
                'last_name' => 'Hakizimana',
                'email' => 'emmanuel.h@worker.rw',
                'phone' => '+250788222333',
                'position' => 'Mason',
                'daily_wage' => 350000, // 350,000 RWF
                'currency' => 'RWF',
                'status' => 'active',
                'hired_at' => now()->subMonths(12),
            ],
            [
                'first_name' => 'Patrick',
                'last_name' => 'Nsengimana',
                'email' => 'patrick.n@worker.rw',
                'phone' => '+250788333444',
                'position' => 'Carpenter',
                'daily_wage' => 380000, // 380,000 RWF
                'currency' => 'RWF',
                'status' => 'active',
                'hired_at' => now()->subMonths(10),
            ],
            [
                'first_name' => 'Claude',
                'last_name' => 'Uwizeyimana',
                'email' => 'claude.u@worker.rw',
                'phone' => '+250788444555',
                'position' => 'Electrician',
                'daily_wage' => 420000, // 420,000 RWF
                'currency' => 'RWF',
                'status' => 'active',
                'hired_at' => now()->subMonths(8),
            ],
            [
                'first_name' => 'Olivier',
                'last_name' => 'Mugabo',
                'email' => 'olivier.m@worker.rw',
                'phone' => '+250788555666',
                'position' => 'Plumber',
                'daily_wage' => 380000, // 380,000 RWF
                'currency' => 'RWF',
                'status' => 'active',
                'hired_at' => now()->subMonths(6),
            ],
            [
                'first_name' => 'Eric',
                'last_name' => 'Niyonzima',
                'email' => 'eric.n@worker.rw',
                'phone' => '+250788666777',
                'position' => 'Welder',
                'daily_wage' => 360000, // 360,000 RWF
                'currency' => 'RWF',
                'status' => 'active',
                'hired_at' => now()->subMonths(5),
            ],
            [
                'first_name' => 'Faustin',
                'last_name' => 'Muhire',
                'email' => 'faustin.m@worker.rw',
                'phone' => '+250788777888',
                'position' => 'Painter',
                'daily_wage' => 320000, // 320,000 RWF
                'currency' => 'RWF',
                'status' => 'active',
                'hired_at' => now()->subMonths(4),
            ],
            [
                'first_name' => 'Gilbert',
                'last_name' => 'Nkurunziza',
                'email' => null,
                'phone' => '+250788888999',
                'position' => 'Laborer',
                'daily_wage' => 200000, // 200,000 RWF
                'currency' => 'RWF',
                'status' => 'active',
                'hired_at' => now()->subMonths(3),
            ],
            [
                'first_name' => 'Innocent',
                'last_name' => 'Habimana',
                'email' => null,
                'phone' => '+250788999000',
                'position' => 'Laborer',
                'daily_wage' => 200000, // 200,000 RWF
                'currency' => 'RWF',
                'status' => 'active',
                'hired_at' => now()->subMonths(2),
            ],
            [
                'first_name' => 'Jacques',
                'last_name' => 'Mutabazi',
                'email' => 'jacques.m@worker.rw',
                'phone' => '+250788000111',
                'position' => 'Driver',
                'daily_wage' => 300000, // 300,000 RWF
                'currency' => 'RWF',
                'status' => 'active',
                'hired_at' => now()->subMonth(),
            ],
        ];

        foreach ($workers as $worker) {
            $worker['tenant_id'] = $tenant->id;
            Worker::firstOrCreate(
                ['email' => $worker['email'], 'tenant_id' => $worker['tenant_id']],
                $worker
            );
        }

        $this->command->info('Workers seeded successfully!');
    }
}
