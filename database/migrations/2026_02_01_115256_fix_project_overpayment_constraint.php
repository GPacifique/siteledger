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
        // Temporarily drop the constraint to allow fixing the data
        try {
            DB::statement('ALTER TABLE projects DROP CHECK projects_positive_remaining_check');
        } catch (Exception $e) {
            // Constraint might not exist or already dropped
        }

        // Fix projects with overpayments - set amount_remaining to 0 for overpaid projects
        DB::statement('
            UPDATE projects
            SET amount_remaining = GREATEST(0, contract_value - amount_paid),
                updated_at = NOW()
            WHERE amount_paid > contract_value
        ');

        // Add back the constraint (only if it doesn't exist)
        $constraintExists = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.CHECK_CONSTRAINTS
            WHERE CONSTRAINT_SCHEMA = DATABASE()
            AND TABLE_NAME = 'projects'
            AND CONSTRAINT_NAME = 'projects_positive_remaining_check'
        ");

        if (empty($constraintExists)) {
            DB::statement('
                ALTER TABLE projects
                ADD CONSTRAINT projects_positive_remaining_check
                CHECK (amount_remaining >= 0)
            ');
        }
    }
};
