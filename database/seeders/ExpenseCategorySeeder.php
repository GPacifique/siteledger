<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExpenseCategory;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Materials',
            'Labor',
            'Equipment',
            'Utilities',
            'Transport',
            'Administrative',
            'Subcontractor',
            'Other',
        ];

        foreach ($categories as $category) {
            ExpenseCategory::firstOrCreate([
                'name' => $category
            ], [
                'active' => true
            ]);
        }

        // Specific labor categories
        $laborCategories = [
            'Mason (Umubatsi)',
            'Bricklayer',
            'Stone Mason',
            'Concrete Worker',
            'Plasterer',
            'Tiler (Floor & Wall tiles)',
            'Carpenter (Formwork & Roofing)',
            'Painter',
            'Steel Fixer (Rebar worker)',
            'Welder',
        ];

        foreach ($laborCategories as $laborCategory) {
            ExpenseCategory::firstOrCreate([
                'name' => $laborCategory
            ], [
                'active' => true
            ]);
        }
    }
}
