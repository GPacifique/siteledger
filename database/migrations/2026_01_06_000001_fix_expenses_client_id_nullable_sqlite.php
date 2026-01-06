<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This migration properly handles SQLite to make client_id nullable.
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            // SQLite requires recreating the table to change column nullability
            DB::statement('PRAGMA foreign_keys=off');

            // Create new table with correct schema
            DB::statement('CREATE TABLE expenses_new (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                tenant_id INTEGER,
                date DATE,
                category VARCHAR(255),
                expense_type VARCHAR(255),
                phase VARCHAR(255),
                item_name VARCHAR(255),
                quantity DECIMAL(10,2),
                unit VARCHAR(255),
                unit_price DECIMAL(12,2),
                description TEXT,
                project_id INTEGER,
                client_id INTEGER,
                amount DECIMAL(12,2) DEFAULT 0,
                method VARCHAR(255),
                status VARCHAR(255),
                user_id INTEGER,
                created_at DATETIME,
                updated_at DATETIME
            )');

            // Copy existing data
            DB::statement('INSERT INTO expenses_new SELECT
                id, tenant_id, date, category, expense_type, phase, item_name,
                quantity, unit, unit_price, description, project_id, client_id,
                amount, method, status, user_id, created_at, updated_at
                FROM expenses');

            // Drop old table
            DB::statement('DROP TABLE expenses');

            // Rename new table
            DB::statement('ALTER TABLE expenses_new RENAME TO expenses');

            DB::statement('PRAGMA foreign_keys=on');

            echo "   ✅ SQLite: client_id is now nullable in expenses table\n";
        } else {
            // MySQL/PostgreSQL - use standard approach
            Schema::table('expenses', function (Blueprint $table) {
                $table->unsignedBigInteger('client_id')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not reversible for SQLite
    }
};
