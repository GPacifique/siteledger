<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('worker_payments') && !Schema::hasColumn('worker_payments', 'project_id')) {
            Schema::table('worker_payments', function (Blueprint $table) {
                $table->foreignId('project_id')->nullable()->after('worker_id')->constrained('projects')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('worker_payments') && Schema::hasColumn('worker_payments', 'project_id')) {
            Schema::table('worker_payments', function (Blueprint $table) {
                $table->dropForeign(['project_id']);
                $table->dropColumn('project_id');
            });
        }
    }
};
