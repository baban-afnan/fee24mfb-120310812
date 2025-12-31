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
        // Update nin_validation table
        DB::statement("
            ALTER TABLE nin_validation 
            MODIFY COLUMN status ENUM(
                'pending',
                'in-progress',
                'processing',
                'query',
                'remark',
                'resolved',
                'successful',
                'failed',
                'rejected'
            ) DEFAULT 'pending'
        ");

        // Update nin_ipe table
        DB::statement("
            ALTER TABLE nin_ipe 
            MODIFY COLUMN status ENUM(
                'pending',
                'in-progress',
                'processing',
                'query',
                'remark',
                'resolved',
                'successful',
                'failed',
                'rejected'
            ) DEFAULT 'pending'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert nin_validation table to previous state
        // Note: This might fail if data with new statuses exists. 
        // We revert to the strict previous list found in original migrations.
        DB::statement("
            ALTER TABLE nin_validation 
            MODIFY COLUMN status ENUM(
                'pending',
                'processing',
                'resolved',
                'rejected',
                'query',
                'remark'
            ) DEFAULT 'pending'
        ");

         // Revert nin_ipe table
         DB::statement("
            ALTER TABLE nin_ipe 
            MODIFY COLUMN status ENUM(
                'pending',
                'processing',
                'resolved',
                'rejected',
                'query',
                'remark'
            ) DEFAULT 'pending'
        ");
    }
};
