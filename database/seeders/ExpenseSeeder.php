<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expense;
use App\Models\Project;
use App\Models\Tenant;
use App\Models\ExpenseCategory;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::with('client')->get();

        if ($projects->isEmpty()) {
            $this->command->warn('No projects found. Please run ProjectSeeder first.');
            return;
        }

        // Get the first tenant
        $tenant = Tenant::first();

        if (!$tenant) {
            $this->command->warn('No tenant found. Please ensure tenants are created first.');
            return;
        }        // Get actual projects
        $project1 = $projects->where('name', 'Kigali Heights Apartment Complex')->first();
        $project2 = $projects->where('name', 'Nyarugenge Commercial Center')->first();
        $project3 = $projects->where('name', 'Kimihurura Villa Project')->first();
        $project4 = $projects->where('name', 'Gacuriro Housing Estate')->first();

        if (!$project1 || !$project2 || !$project3 || !$project4) {
            $this->command->warn('Some projects not found. Skipping expense seeding.');
            return;
        }

        $categories = ExpenseCategory::all()->keyBy(function($cat) {
            return strtolower($cat->name);
        });

        $expenses = [
            // Material expenses
            [
                'project_id' => $project1->id,
                'client_id' => $project1->client_id,
                'category' => 'Materials',
                'quantity' => 1000,
                'unit' => 'bags',
                'unit_price' => 50000,
                'date' => now()->subMonths(3),
            ],
            [
                'project_id' => $project1->id,
                'client_id' => $project1->client_id,
                'category' => 'Materials',
                'quantity' => 600,
                'unit' => 'blocks',
                'unit_price' => 50000,
                'date' => now()->subMonths(2),
            ],
            [
                'project_id' => $project2->id,
                'client_id' => $project2->client_id,
                'category' => 'Materials',
                'quantity' => 1000,
                'unit' => 'sqm',
                'unit_price' => 80000,
                'date' => now()->subMonths(4),
            ],
            // Labor expenses
            [
                'project_id' => $project1->id,
                'client_id' => $project1->client_id,
                'category' => 'Labor',
                'quantity' => 1000,
                'unit' => 'hours',
                'price_per_one' => 25000,
                'date' => now()->subMonth(),
            ],
            [
                'project_id' => $project2->id,
                'client_id' => $project2->client_id,
                'category' => 'Labor',
                'quantity' => 1400,
                'unit' => 'hours',
                'price_per_one' => 25000,
                'date' => now()->subMonth(),
            ],
            // Equipment expenses
            [
                'project_id' => $project1->id,
                'client_id' => $project1->client_id,
                'category' => 'Equipment',
                'quantity' => 30,
                'unit' => 'days',
                'unit_price' => 500000,
                'date' => now()->subMonths(2),
            ],
            [
                'project_id' => $project2->id,
                'client_id' => $project2->client_id,
                'category' => 'Equipment',
                'quantity' => 40,
                'unit' => 'days',
                'unit_price' => 500000,
                'date' => now()->subMonths(3),
            ],
            // Utilities
            [
                'project_id' => $project1->id,
                'client_id' => $project1->client_id,
                'category' => 'Utilities',
                'quantity' => 2,
                'unit' => 'months',
                'unit_price' => 1000000,
                'date' => now()->subMonth(),
            ],
            [
                'project_id' => $project2->id,
                'client_id' => $project2->client_id,
                'category' => 'Utilities',
                'quantity' => 2,
                'unit' => 'months',
                'unit_price' => 1500000,
                'date' => now()->subMonth(),
            ],
            // Transport
            [
                'project_id' => $project1->id,
                'client_id' => $project1->client_id,
                'category' => 'Transport',
                'quantity' => 10,
                'unit' => 'trips',
                'unit_price' => 500000,
                'date' => now()->subWeeks(2),
            ],
            // Completed project expenses
            [
                'project_id' => $project3->id,
                'client_id' => $project3->client_id,
                'category' => 'Materials',
                'quantity' => 2000,
                'unit' => 'bags',
                'unit_price' => 60000,
                'date' => now()->subMonths(10),
            ],
            [
                'project_id' => $project3->id,
                'client_id' => $project3->client_id,
                'category' => 'Labor',
                'quantity' => 3200,
                'unit' => 'hours',
                'price_per_one' => 25000,
                'date' => now()->subMonths(8),
            ],
            // Recent expenses (this week)
            [
                'project_id' => $project1->id,
                'client_id' => $project1->client_id,
                'category' => 'Materials',
                'quantity' => 100,
                'unit' => 'cans',
                'unit_price' => 80000,
                'date' => now()->subDays(3),
            ],
            [
                'project_id' => $project4->id,
                'client_id' => $project4->client_id,
                'category' => 'Materials',
                'quantity' => 200,
                'unit' => 'bags',
                'unit_price' => 60000,
                'date' => now()->subDays(5),
            ],
        ];

        foreach ($expenses as $expense) {
            $categoryName = strtolower($expense['category']);
            $expense_category_id = $categories[$categoryName]->id ?? null;
            $insert = [
                'project_id' => $expense['project_id'],
                'client_id' => $expense['client_id'],
                'expense_category_id' => $expense_category_id,
                'quantity' => $expense['quantity'],
                'unit' => $expense['unit'],
                'date' => $expense['date'],
            ];
            if (isset($expense['unit_price'])) {
                $insert['unit_price'] = $expense['unit_price'];
            }
            if (isset($expense['price_per_one'])) {
                $insert['price_per_one'] = $expense['price_per_one'];
            }
            // Always calculate total as quantity * (unit_price or price_per_one)
            $unitPrice = $expense['unit_price'] ?? $expense['price_per_one'] ?? 0;
            $insert['total'] = $expense['quantity'] * $unitPrice;
            Expense::query()->insert([$insert]);
        }

        $this->command->info('Expenses seeded successfully!');
    }
}
