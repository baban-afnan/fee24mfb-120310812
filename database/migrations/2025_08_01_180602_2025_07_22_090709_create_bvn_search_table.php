<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
       Schema::create('bvn_search', function (Blueprint $table) {
    $table->id();
    $table->string('reference')->unique(); // This is required and must be provided
    $table->foreignId('user_id')->constrained();
    $table->foreignId('modification_field_id')->constrained('modification_fields');
    $table->foreignId('service_id')->constrained('services');
    $table->string('number')->nullable();
    $table->string('bvn')->nullable();
    $table->string('first_name')->nullable();
    $table->string('last_name')->nullable();
    $table->string('middle_name')->nullable();
    $table->string('gender')->nullable();
    $table->string('dob')->nullable(); 
    $table->string('email')->nullable();
    $table->string('lga')->nullable();   
    $table->string('state')->nullable(); 
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
        Schema::dropIfExists('bvn_search');
    }
};