<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Worker;
use App\Models\WorkerPayment;
use App\Models\Tenant;
use Carbon\Carbon;

class WorkerPaymentSeeder extends Seeder
{
    /**
     * Seed worker payments based on existing tasks and workers.
     */
    public function run(): void
    {
        // Get the first tenant or create one
        $tenant = Tenant::first();
        if (!$tenant) {
            $this->command->error('No tenant found. Please seed tenants first.');
            return;
        }

        // Get all tasks with assigned workers
        $tasks = Task::whereNotNull('assigned_to')
            ->whereNotNull('project_id')
            ->with(['project'])
            ->get();

        // Group tasks by worker and project
        $workerProjects = $tasks->groupBy(function($task) {
            return $task->assigned_to . '-' . $task->project_id;
        });

        foreach ($workerProjects as $key => $tasksGroup) {
            [$workerId, $projectId] = explode('-', $key);

            $worker = Worker::find($workerId);
            if (!$worker) continue;

            // Generate 2-5 payments per worker per project
            $paymentCount = rand(2, 5);

            for ($i = 0; $i < $paymentCount; $i++) {
                // Random date in the last 3 months
                $paidOn = Carbon::now()->subDays(rand(1, 90))->format('Y-m-d');

                // Check if payment already exists for this worker/project/date
                $exists = WorkerPayment::where('worker_id', $workerId)
                    ->where('project_id', $projectId)
                    ->where('paid_on', $paidOn)
                    ->exists();

                if ($exists) continue;

                // Amount based on worker position
                $baseAmount = match(strtolower($worker->position ?? '')) {
                    'engineer', 'senior engineer' => rand(80000, 150000),
                    'foreman', 'supervisor' => rand(60000, 100000),
                    'technician' => rand(40000, 80000),
                    'laborer', 'helper' => rand(20000, 40000),
                    default => rand(30000, 70000),
                };

                WorkerPayment::create([
                    'tenant_id' => $worker->tenant_id ?? $tenant->id,
                    'worker_id' => $workerId,
                    'project_id' => $projectId,
                    'paid_on' => $paidOn,
                    'amount' => $baseAmount,
                    'notes' => 'Payment for ' . Carbon::parse($paidOn)->format('F Y'),
                ]);
            }
        }

        $this->command->info('Worker payments seeded successfully!');
        $this->command->info('Total payments created: ' . WorkerPayment::count());
    }
}
