<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Set default phase for all existing labor expenses where phase is null or empty
        DB::table('expenses')
            ->where('expense_type', 'labor')
            ->where(function($q) {
                $q->whereNull('phase')->orWhere('phase', '');
            })
            ->update(['phase' => 'design']);

        // Make phase NOT NULL for labor expenses
        Schema::table('expenses', function (Blueprint $table) {
            $table->string('phase')->default('design')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        // Allow phase to be nullable again
        Schema::table('expenses', function (Blueprint $table) {
            $table->string('phase')->nullable()->default(null)->change();
        });
    }
};
