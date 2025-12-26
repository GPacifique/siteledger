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
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'project_code')) {
                $table->string('project_code')->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('projects', 'manager_id')) {
                $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('projects', 'budget')) {
                $table->decimal('budget', 14, 2)->nullable()->default(0);
            }
            if (!Schema::hasColumn('projects', 'priority')) {
                $table->string('priority')->nullable();
            }
            if (!Schema::hasColumn('projects', 'description')) {
                $table->text('description')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'project_code')) {
                $table->dropColumn('project_code');
            }
            if (Schema::hasColumn('projects', 'manager_id')) {
                $table->dropColumn('manager_id');
            }
            if (Schema::hasColumn('projects', 'budget')) {
                $table->dropColumn('budget');
            }
            if (Schema::hasColumn('projects', 'priority')) {
                $table->dropColumn('priority');
            }
            if (Schema::hasColumn('projects', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
