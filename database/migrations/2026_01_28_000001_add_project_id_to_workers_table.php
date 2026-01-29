<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('workers') && !Schema::hasColumn('workers', 'project_id')) {
            Schema::table('workers', function (Blueprint $table) {
                $table->foreignId('project_id')->nullable()->after('tenant_id')->constrained('projects')->onDelete('set null');
                $table->index(['tenant_id', 'project_id'], 'workers_tenant_project_id_index');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('workers') && Schema::hasColumn('workers', 'project_id')) {
            Schema::table('workers', function (Blueprint $table) {
                $table->dropIndex('workers_tenant_project_id_index');
                $table->dropConstrainedForeignId('project_id');
            });
        }
    }
};
