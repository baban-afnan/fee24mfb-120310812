<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
       Schema::create('bvn_crm', function (Blueprint $table) {
    $table->id();
    $table->string('reference')->unique(); 
    $table->foreignId('user_id')->constrained();
    $table->foreignId('modification_field_id')->constrained('modification_fields');
    $table->foreignId('service_id')->constrained('services');
    $table->string('ticket_id', 8);
    $table->string('batch_id', 7);
    $table->string('performed_by', 150)->nullable();
    $table->string('approved_by', 150)->nullable();
    $table->foreignId('transaction_id')->constrained();
    $table->dateTime('submission_date');
    $table->enum('status', ['pending', 'processing', 'resolved', 'rejected', 'query', 'remark'])->default('pending');
    $table->text('comment')->nullable();
    $table->timestamps();
     });

    }

    public function down(): void
    {
        Schema::dropIfExists('bvn_crm');
    }
};