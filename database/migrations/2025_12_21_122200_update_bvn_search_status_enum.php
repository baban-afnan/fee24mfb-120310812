<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     * Updates status enum for bvn_search table
     */
    public function up(): void
    {
        // Update bvn_search status enum
        if (Schema::hasTable('bvn_search')) {
            DB::statement("ALTER TABLE bvn_search MODIFY COLUMN status ENUM('pending', 'in-progress', 'processing', 'query', 'remark', 'resolved', 'successful', 'failed', 'rejected') DEFAULT 'pending'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('bvn_search')) {
            DB::statement("ALTER TABLE bvn_search MODIFY COLUMN status ENUM('pending', 'processing', 'resolved', 'rejected', 'Verified') DEFAULT 'pending'");
        }
    }
};
