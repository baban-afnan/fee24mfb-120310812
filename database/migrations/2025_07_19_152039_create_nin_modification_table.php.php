<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('nin_modifications', function (Blueprint $table) {
          $table->id();
          $table->string('reference')->unique();
          $table->foreignId('user_id')->constrained();
          $table->foreignId('modification_field_id')->constrained('modification_fields');
          $table->foreignId('service_id')->constrained('services');
          $table->string('nin', 11);
          $table->text('description');
          $table->foreignId('transaction_id')->constrained();
          $table->dateTime('submission_date');
          $table->enum('status', ['pending', 'processing', 'resolved', 'rejected', 'query', 'remark'])->default('pending');
          $table->text('comment')->nullable();
          $table->string('performed_by', 150)->nullable();
          $table->string('approved_by', 150)->nullable(); 
          $table->timestamps();
         });
    }

    public function down(): void
    {
        Schema::dropIfExists('nin_modifications');
    }
};
