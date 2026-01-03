<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('workers') && !Schema::hasColumn('workers', 'created_by')) {
            Schema::table('workers', function (Blueprint $table) {
                $table->foreignId('created_by')->nullable()->after('tenant_id')
                      ->constrained('users')->onDelete('set null');
                $table->index(['tenant_id', 'created_by'], 'workers_tenant_created_by_index');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('workers') && Schema::hasColumn('workers', 'created_by')) {
            Schema::table('workers', function (Blueprint $table) {
                $table->dropIndex('workers_tenant_created_by_index');
                $table->dropConstrainedForeignId('created_by');
            });
        }
    }
};
