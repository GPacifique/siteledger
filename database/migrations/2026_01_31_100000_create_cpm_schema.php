<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // projects
        if (!Schema::hasTable('projects')) {
            Schema::create('projects', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->nullable();
                $table->string('name');
                $table->foreignId('client_id')->nullable();
                $table->string('location')->nullable();
                $table->enum('project_type', ['DESIGN', 'EXECUTION', 'DESIGN_EXECUTION'])->default('EXECUTION');
                $table->decimal('budget', 14, 2)->default(0);
                $table->decimal('contract_value', 14, 2)->nullable();
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->enum('status', ['planning','active','on_hold','completed','cancelled'])->default('planning');
                $table->json('meta')->nullable();
                $table->timestamps();
                $table->index(['tenant_id']);
            });
        }

        // workers
        if (!Schema::hasTable('workers')) {
            Schema::create('workers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->nullable();
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('role')->nullable();
                $table->decimal('hourly_rate', 12, 2)->nullable();
                $table->enum('status', ['active','inactive'])->default('active');
                $table->json('contact')->nullable();
                $table->timestamps();
                $table->index(['tenant_id']);
            });
        }

        // materials
        if (!Schema::hasTable('materials')) {
            Schema::create('materials', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->nullable();
                $table->string('name');
                $table->string('unit')->nullable();
                $table->decimal('unit_price', 14, 2)->nullable();
                $table->timestamps();
            });
        }

        // expense categories
        if (!Schema::hasTable('expense_categories')) {
            Schema::create('expense_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->boolean('active')->default(true);
                $table->timestamps();
            });
        }

        // phases
        if (!Schema::hasTable('phases')) {
            Schema::create('phases', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
                $table->integer('position')->default(0);
                $table->string('name');
                $table->date('planned_start')->nullable();
                $table->date('planned_end')->nullable();
                $table->date('actual_start')->nullable();
                $table->date('actual_end')->nullable();
                $table->enum('status', ['pending','in_progress','completed','on_hold','cancelled'])->default('pending');
                $table->decimal('budget', 14, 2)->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->index(['project_id']);
            });
        }

        // tasks
        if (!Schema::hasTable('tasks')) {
            Schema::create('tasks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('phase_id')->constrained('phases')->onDelete('cascade');
                $table->string('title');
                $table->text('description')->nullable();
                $table->foreignId('assigned_to')->nullable()->constrained('workers')->nullOnDelete();
                $table->date('planned_start')->nullable();
                $table->date('planned_end')->nullable();
                $table->date('actual_start')->nullable();
                $table->date('actual_end')->nullable();
                $table->enum('status', ['todo','in_progress','blocked','done'])->default('todo');
                $table->decimal('hours_estimated', 8, 2)->nullable();
                $table->decimal('hours_spent', 8, 2)->nullable();
                $table->timestamps();
                $table->index(['phase_id','assigned_to']);
            });
        }

        // project_workers (assign workers to projects/phases)
        if (!Schema::hasTable('project_workers')) {
            Schema::create('project_workers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
                $table->foreignId('worker_id')->constrained('workers')->onDelete('cascade');
                $table->foreignId('phase_id')->nullable()->constrained('phases')->nullOnDelete();
                $table->string('role_on_project')->nullable();
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->timestamps();
                $table->unique(['project_id','worker_id','phase_id'],'proj_worker_phase_unique');
            });
        }

        // expenses
        if (!Schema::hasTable('expenses')) {
            Schema::create('expenses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
                $table->foreignId('phase_id')->nullable()->constrained('phases')->nullOnDelete();
                $table->foreignId('expense_category_id')->nullable()->constrained('expense_categories')->nullOnDelete();
                $table->string('expense_type')->nullable();
                $table->string('item_name')->nullable();
                $table->decimal('quantity', 12, 3)->nullable();
                $table->string('unit')->nullable();
                $table->decimal('unit_price', 14, 2)->nullable();
                $table->decimal('price_per_one', 14, 2)->nullable();
                $table->decimal('total', 14, 2)->default(0);
                $table->date('date')->nullable();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('tenant_id')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->index(['project_id','phase_id']);
            });
        }

        // payments
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
                $table->decimal('amount', 14, 2);
                $table->date('payment_date');
                $table->string('method')->nullable();
                $table->string('reference')->nullable();
                $table->enum('status', ['pending','paid','failed'])->default('pending');
                $table->timestamps();
                $table->index(['project_id','payment_date']);
            });
        }

        // phase_costs (materialized aggregates)
        if (!Schema::hasTable('phase_costs')) {
            Schema::create('phase_costs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
                $table->foreignId('phase_id')->constrained('phases')->onDelete('cascade');
                $table->decimal('labor_total', 14, 2)->default(0);
                $table->decimal('materials_total', 14, 2)->default(0);
                $table->decimal('equipment_total', 14, 2)->default(0);
                $table->decimal('transport_total', 14, 2)->default(0);
                $table->decimal('other_total', 14, 2)->default(0);
                $table->decimal('total', 14, 2)->default(0);
                $table->timestamp('calculated_at')->nullable();
                $table->unique(['project_id','phase_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('phase_costs');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('project_workers');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('phases');
        Schema::dropIfExists('expense_categories');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('workers');
        Schema::dropIfExists('projects');
    }
};
