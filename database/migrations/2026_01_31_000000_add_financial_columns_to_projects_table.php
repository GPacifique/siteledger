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
            if (!Schema::hasColumn('projects', 'amount_spent')) {
                $table->decimal('amount_spent', 14, 2)->default(0)->after('amount_paid');
            }
            if (!Schema::hasColumn('projects', 'profit')) {
                $table->decimal('profit', 14, 2)->default(0)->after('amount_spent');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'amount_spent')) {
                $table->dropColumn('amount_spent');
            }
            if (Schema::hasColumn('projects', 'profit')) {
                $table->dropColumn('profit');
            }
        });
    }
};
