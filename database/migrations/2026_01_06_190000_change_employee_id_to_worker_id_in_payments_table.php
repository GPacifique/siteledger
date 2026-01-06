<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Changes employee_id foreign key from employees table to workers table
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Drop the existing foreign key constraint to employees
            $table->dropForeign(['employee_id']);
        });

        Schema::table('payments', function (Blueprint $table) {
            // Add new foreign key constraint to workers table
            $table->foreign('employee_id')
                  ->references('id')
                  ->on('workers')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Drop the workers foreign key
            $table->dropForeign(['employee_id']);
        });

        Schema::table('payments', function (Blueprint $table) {
            // Restore the employees foreign key
            $table->foreign('employee_id')
                  ->references('id')
                  ->on('employees')
                  ->onDelete('set null');
        });
    }
};
