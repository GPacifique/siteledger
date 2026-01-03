<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('workers')) {
            Schema::table('workers', function (Blueprint $table) {
                // Attempt to drop unique indexes if they exist
                try { $table->dropUnique('workers_email_unique'); } catch (\Throwable $e) {}
                try { $table->dropUnique('workers_tenant_email_unique'); } catch (\Throwable $e) {}

                // Optionally, add a non-unique index for email
                try { $table->index(['email'], 'workers_email_index'); } catch (\Throwable $e) {}
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('workers')) {
            Schema::table('workers', function (Blueprint $table) {
                // Recreate tenant-scoped unique if needed (rollback)
                try { $table->unique(['tenant_id', 'email'], 'workers_tenant_email_unique'); } catch (\Throwable $e) {}
                // Global unique is intentionally not restored to avoid conflicts
                try { $table->dropIndex('workers_email_index'); } catch (\Throwable $e) {}
            });
        }
    }
};
