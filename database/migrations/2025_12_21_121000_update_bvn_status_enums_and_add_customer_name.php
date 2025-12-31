<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     * Updates status enum for send_vnin and bvn_crm tables and adds customer_full_name to send_vnin
     */
    public function up(): void
    {
        // Update send_vnin status enum and add customer_full_name column
        DB::statement("ALTER TABLE send_vnin MODIFY COLUMN status ENUM('pending', 'in-progress', 'processing', 'query', 'remark', 'resolved', 'successful', 'failed', 'rejected') DEFAULT 'pending'");
        
        if (!Schema::hasColumn('send_vnin', 'customer_full_name')) {
            Schema::table('send_vnin', function (Blueprint $table) {
                $table->string('customer_full_name', 150)->nullable()->after('field');
            });
        }

        // Update bvn_crm status enum
        DB::statement("ALTER TABLE bvn_crm MODIFY COLUMN status ENUM('pending', 'in-progress', 'processing', 'query', 'remark', 'resolved', 'successful', 'failed', 'rejected') DEFAULT 'pending'");

        // Update bvn_modification status enum if it exists
        if (Schema::hasTable('bvn_modification')) {
            DB::statement("ALTER TABLE bvn_modification MODIFY COLUMN status ENUM('pending', 'in-progress', 'processing', 'query', 'remark', 'resolved', 'successful', 'failed', 'rejected') DEFAULT 'pending'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert send_vnin status enum
        DB::statement("ALTER TABLE send_vnin MODIFY COLUMN status ENUM('pending', 'processing', 'resolved', 'rejected', 'query', 'remark') DEFAULT 'pending'");
        
        Schema::table('send_vnin', function (Blueprint $table) {
            $table->dropColumn('customer_full_name');
        });

        // Revert bvn_crm status enum
        DB::statement("ALTER TABLE bvn_crm MODIFY COLUMN status ENUM('pending', 'processing', 'resolved', 'rejected', 'query', 'remark') DEFAULT 'pending'");

        // Revert bvn_modification status enum if it exists
        if (Schema::hasTable('bvn_modification')) {
            DB::statement("ALTER TABLE bvn_modification MODIFY COLUMN status ENUM('pending', 'processing', 'resolved', 'rejected', 'query') DEFAULT 'pending'");
        }
    }
};
