<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('workers', function (Blueprint $table) {
            $table->renameColumn('salary_cents', 'daily_wage');
        });

        // Convert cents to RWF (divide by 100)
        DB::table('workers')->update([
            'daily_wage' => DB::raw('daily_wage / 100')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert RWF back to cents (multiply by 100)
        DB::table('workers')->update([
            'daily_wage' => DB::raw('daily_wage * 100')
        ]);

        Schema::table('workers', function (Blueprint $table) {
            $table->renameColumn('daily_wage', 'salary_cents');
        });
    }
};
