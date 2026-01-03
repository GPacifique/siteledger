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
        Schema::create('worker_positions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('name')->unique(); // Site Supervisor, Laborer, etc.
            $table->text('description')->nullable();
            $table->decimal('hourly_rate', 10, 2)->nullable(); // Base hourly rate
            $table->decimal('daily_rate', 10, 2)->nullable(); // Base daily rate
            $table->string('category')->nullable(); // Management, Labor, Technical, etc.
            $table->integer('seniority_level')->default(1); // 1=Junior, 2=Mid, 3=Senior
            $table->boolean('is_active')->default(true);
            $table->integer('worker_count')->default(0); // Cached count
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->index('tenant_id');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worker_positions');
    }
};
