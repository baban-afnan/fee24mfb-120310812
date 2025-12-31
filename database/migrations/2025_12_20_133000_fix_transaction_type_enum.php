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
        // Explicitly set the ENUM to allow credit, debit, and refund
        DB::statement("ALTER TABLE transactions MODIFY COLUMN type ENUM('credit', 'debit', 'refund', 'chargeback') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is tricky to reverse if data exists, but we can leave it or revert to a previous state if known.
        // For safety, we keep the expanded list or revert to the 'refund', 'chargeback' widely if that was the intent, 
        // but likely we just want to allow everything that might be there.
        DB::statement("ALTER TABLE transactions MODIFY COLUMN type ENUM('refund', 'chargeback') NOT NULL");
    }
};
