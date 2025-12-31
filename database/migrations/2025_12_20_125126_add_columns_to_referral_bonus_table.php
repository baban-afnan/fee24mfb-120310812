<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('referral_bonus', function (Blueprint $table) {
            $table->string('title')->nullable()->after('id');
            $table->text('description')->nullable()->after('title');
            $table->string('icon')->nullable()->after('description'); // URL or class name for icon
            $table->boolean('status')->default(true)->after('bonus');
            $table->string('type')->default('general')->after('status'); // logic type if needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referral_bonus', function (Blueprint $table) {
            $table->dropColumn(['title', 'description', 'icon', 'status', 'type']);
        });
    }
};
