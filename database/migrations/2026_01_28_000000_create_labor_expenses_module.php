<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('laborers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category'); // Role or category
            $table->string('status')->default('active'); // active/inactive
            $table->timestamps();
        });

        Schema::create('labor_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laborer_id')->constrained('laborers')->onDelete('cascade');
            $table->date('date');
            $table->decimal('units', 8, 2); // days, hours, or tasks
            $table->decimal('rate', 10, 2); // rate per unit
            $table->decimal('amount', 12, 2); // auto-calculated
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('labor_expenses');
        Schema::dropIfExists('laborers');
    }
};
