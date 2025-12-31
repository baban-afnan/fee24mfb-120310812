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
        // Add 'refund' to the ENUM options for the 'type' column
        DB::statement("ALTER TABLE transactions MODIFY COLUMN type ENUM('credit', 'debit', 'refund') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original ENUM values
        DB::statement("ALTER TABLE transactions MODIFY COLUMN type ENUM('credit', 'debit') NOT NULL");
    }
};
