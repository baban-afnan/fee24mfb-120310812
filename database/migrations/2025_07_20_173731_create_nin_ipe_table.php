<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('nin_ipe', function (Blueprint $table) {
          $table->id();
          $table->string('reference')->unique();
          $table->foreignId('user_id')->constrained();
          $table->foreignId('modification_field_id')->constrained('modification_fields');
          $table->foreignId('service_id')->constrained('services');
          $table->text('tracking_id', 15);
          $table->foreignId('transaction_id')->constrained();
          $table->dateTime('submission_date');
          $table->string('performed_by', 150)->nullable();
          $table->string('approved_by', 150)->nullable();
          $table->enum('status', ['pending', 'processing', 'resolved', 'rejected', 'query', 'remark'])->default('pending');
          $table->text('comment')->nullable();
          $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('nin_ipe');
    }
};
