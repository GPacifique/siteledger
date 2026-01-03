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
        Schema::table('expenses', function (Blueprint $table) {
            // Drop existing foreign key constraint
            $table->dropForeign(['client_id']);

            // Modify client_id to be nullable
            $table->foreignId('client_id')->nullable()->change()->constrained('clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            // Drop the nullable foreign key
            $table->dropForeign(['client_id']);

            // Restore client_id to be not nullable
            $table->foreignId('client_id')->nullable(false)->change()->constrained('clients')->onDelete('cascade');
        });
    }
};
