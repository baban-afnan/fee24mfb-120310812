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
        $tables = [
            'bvn_modification',
            'nin_modifications',
            'crm_requests',
            'bvn_searches',
            'bvn_user', // Enrolment User
            'send_vnins',
            'validations',
            'ipes'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                // Use raw SQL to modify ENUM to avoid doctrine/dbal issues with enum mapping
                DB::statement("ALTER TABLE `$table` MODIFY COLUMN `status` ENUM(
                    'pending',
                    'in-progress',
                    'processing',
                    'query',
                    'remark',
                    'resolved',
                    'successful',
                    'failed',
                    'rejected'
                ) NOT NULL DEFAULT 'pending'");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to a simpler enum set if needed, or leave as is since it's an additive change mostly.
        // For safety, we can revert to a common subset or previous state if known, but usually modifying enums back is risky if data exists.
        // We will skip strict reversion to avoid data loss.
    }
};
