<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('project_phase_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained('tenants')->onDelete('cascade');
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->enum('phase', ['design', 'execution']);
            $table->decimal('amount', 14, 2);
            $table->date('payment_date');
            $table->string('payment_method')->nullable(); // cash, bank_transfer, check, etc.
            $table->string('reference_number')->nullable(); // invoice/receipt number
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('completed');
            $table->foreignId('received_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            // Index for faster queries
            $table->index(['project_id', 'phase']);
            $table->index('payment_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_phase_payments');
    }
};
