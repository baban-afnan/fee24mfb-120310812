<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    /**
     * Run the migrations.
     * Status workflow:
     * pending → in-progress → processing → query → remark → resolved/successful → failed/rejected
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE bvn_modification 
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
        DB::statement("
            ALTER TABLE bvn_modification 
            MODIFY COLUMN status ENUM(
                'pending',
                'processing',
                'resolved',
                'failed'
            ) DEFAULT 'pending'
        ");
    }
};
