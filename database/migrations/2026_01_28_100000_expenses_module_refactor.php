<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects');
            $table->foreignId('expense_category_id')->constrained('expense_categories');
            $table->foreignId('client_id')->nullable()->constrained('clients')->onDelete('cascade');
            // expense_type: materials, labor, equipment, transport, etc.
            $table->string('expense_type')->nullable();
            // phase: design, execution (links to project phases)
            $table->string('phase')->nullable();
            // item_name for materials tracking
            $table->string('item_name')->nullable();
            // quantity for materials
            $table->decimal('quantity', 12, 2)->nullable();
            // unit for materials (pieces, kg, bags, etc.)
            $table->string('unit')->nullable();
            // unit_price for materials
            $table->decimal('unit_price', 12, 2)->nullable();
            // price_per_one for labor/other
            $table->decimal('price_per_one', 12, 2)->nullable();
            // total for all
            $table->decimal('total', 14, 2)->nullable();
            $table->date('date');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('tenant_id')->nullable()->constrained('tenants')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('expense_categories');
    }
};
