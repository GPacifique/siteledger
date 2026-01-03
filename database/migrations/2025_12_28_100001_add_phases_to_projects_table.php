<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            // Phase information
            $table->enum('current_phase', ['design', 'execution'])->default('design')->after('status');

            // Design phase values
            $table->decimal('design_phase_value', 14, 2)->default(0)->after('current_phase');
            $table->decimal('design_phase_paid', 14, 2)->default(0)->after('design_phase_value');
            $table->date('design_start_date')->nullable()->after('design_phase_paid');
            $table->date('design_end_date')->nullable()->after('design_start_date');
            $table->enum('design_phase_status', ['pending', 'in_progress', 'completed'])->default('pending')->after('design_end_date');

            // Execution phase values
            $table->decimal('execution_phase_value', 14, 2)->default(0)->after('design_phase_status');
            $table->decimal('execution_phase_paid', 14, 2)->default(0)->after('execution_phase_value');
            $table->date('execution_start_date')->nullable()->after('execution_phase_paid');
            $table->date('execution_end_date')->nullable()->after('execution_start_date');
            $table->enum('execution_phase_status', ['pending', 'in_progress', 'completed'])->default('pending')->after('execution_end_date');
        });
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'current_phase',
                'design_phase_value',
                'design_phase_paid',
                'design_start_date',
                'design_end_date',
                'design_phase_status',
                'execution_phase_value',
                'execution_phase_paid',
                'execution_start_date',
                'execution_end_date',
                'execution_phase_status',
            ]);
        });
    }
};
