<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bvn_modification', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('modification_field_id')->nullable();
            $table->string('service_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('modification_field_name')->nullable();
            $table->string('service_name')->nullable();
            $table->string('bank')->nullable();
            $table->string('bvn', 50);
            $table->string('nin', 50);
            $table->string('description', 150);
            $table->string('performed_by', 150)->nullable();
            $table->string('approved_by', 150)->nullable();
            $table->string('affidavit', 50);
            $table->string('affidavit_file')->nullable();
            $table->string('affidavit_file_url')->nullable();
            $table->dateTime('submission_date');
            $table->enum('status', ['pending', 'processing', 'resolved', 'rejected', 'query'])->default('pending');
            $table->text('comment')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bvn_modifications');
    }
};
