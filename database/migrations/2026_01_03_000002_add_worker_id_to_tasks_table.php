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
        // Add worker_id column to tasks table to link tasks directly to workers
        if (Schema::hasTable('tasks') && !Schema::hasColumn('tasks', 'worker_id')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->foreignId('worker_id')->nullable()->after('assigned_to')->constrained('workers')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('tasks') && Schema::hasColumn('tasks', 'worker_id')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->dropForeign(['worker_id']);
                $table->dropColumn('worker_id');
            });
        }
    }
};
