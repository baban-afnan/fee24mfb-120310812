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
        Schema::create('selfservice', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('modification_field_id')->constrained('modification_fields')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->string('service_name')->nullable();
            $table->string('modification_field_name')->nullable();
            $table->string('nin', 11);
            $table->foreignId('transaction_id');
            $table->dateTime('submission_date');
            $table->enum('status', ['pending', 'processing', 'resolved', 'rejected', 'query', 'remark'])->default('pending');
            $table->text('comment')->nullable();
            $table->string('performed_by', 150)->nullable();
            $table->string('approved_by', 150)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('selfservice');
    }
};
