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
        Schema::create('report', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();
            $table->string('bank_code')->nullable();
            $table->string('bank_name')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->decimal('amount', 15, 2)->default(0.00);
            $table->string('phone_number')->nullable();
            $table->string('network')->nullable();
            $table->string('ref')->nullable(); // Reference can sometimes be null or generated later? Assuming nullable to be safe, or string.
            $table->string('status')->default('pending');
            $table->string('type')->nullable(); // e.g., 'cable', 'airtime'
            $table->text('description')->nullable();
            $table->decimal('old_balance', 15, 2)->default(0.00);
            $table->decimal('new_balance', 15, 2)->default(0.00);
            $table->string('service_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report');
    }
};
