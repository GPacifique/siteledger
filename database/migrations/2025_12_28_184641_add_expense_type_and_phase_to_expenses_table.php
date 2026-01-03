<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            // expense_type: materials, labor, equipment, transport, etc.
            $table->string('expense_type')->nullable()->after('category');
            // phase: design, execution (links to project phases)
            $table->string('phase')->nullable()->after('expense_type');
            // item_name for materials tracking
            $table->string('item_name')->nullable()->after('phase');
            // quantity for materials
            $table->decimal('quantity', 12, 2)->nullable()->after('item_name');
            // unit for materials (pieces, kg, bags, etc.)
            $table->string('unit')->nullable()->after('quantity');
            // unit_price for materials
            $table->decimal('unit_price', 12, 2)->nullable()->after('unit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn(['expense_type', 'phase', 'item_name', 'quantity', 'unit', 'unit_price']);
        });
    }
};
