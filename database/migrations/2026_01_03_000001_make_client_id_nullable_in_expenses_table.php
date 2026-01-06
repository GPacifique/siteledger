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
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            // SQLite doesn't support ALTER TABLE MODIFY, so we need to recreate the table
            // But SQLite does allow nullable columns through a simpler approach using Schema
            // Check if client_id is already nullable by examining the schema
            // For SQLite, we'll use a workaround - the column might already be nullable
            // Skip the modification for SQLite as it's more complex
            echo "   ℹ️ SQLite: Skipping client_id modification (SQLite limitations)\n";
            return;
        }

        // MySQL/MariaDB approach
        // Drop existing foreign key if it exists
        $constraintName = $this->getForeignKeyName('expenses', 'client_id');
        if ($constraintName) {
            DB::statement("ALTER TABLE `expenses` DROP FOREIGN KEY `{$constraintName}`");
        }

        // Make the column nullable without requiring doctrine/dbal
        DB::statement('ALTER TABLE `expenses` MODIFY `client_id` BIGINT UNSIGNED NULL');

        // Re-add the foreign key (keep cascade behavior)
        Schema::table('expenses', function (Blueprint $table) {
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            // Skip for SQLite
            return;
        }

        // Drop foreign key if exists
        $constraintName = $this->getForeignKeyName('expenses', 'client_id');
        if ($constraintName) {
            DB::statement("ALTER TABLE `expenses` DROP FOREIGN KEY `{$constraintName}`");
        }

        // Restore NOT NULL
        DB::statement('ALTER TABLE `expenses` MODIFY `client_id` BIGINT UNSIGNED NOT NULL');

        // Re-add the foreign key
        Schema::table('expenses', function (Blueprint $table) {
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    /**
     * Resolve foreign key name for a table/column, if present.
     */
    private function getForeignKeyName(string $table, string $column): ?string
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            return null;
        }

        $dbName = DB::getDatabaseName();
        $rows = DB::select(
            'SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? AND REFERENCED_TABLE_NAME IS NOT NULL LIMIT 1',
            [$dbName, $table, $column]
        );
        if (!empty($rows)) {
            return $rows[0]->CONSTRAINT_NAME ?? null;
        }
        // Fallback to Laravel convention
        $conventional = $table . '_' . $column . '_foreign';
        // Check if conventional name exists
        $rows2 = DB::select(
            'SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_NAME = ? AND CONSTRAINT_TYPE = "FOREIGN KEY" LIMIT 1',
            [$dbName, $table, $conventional]
        );
        return !empty($rows2) ? $conventional : null;
    }
};
