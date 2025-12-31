<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_id')->unique();   
            $table->string('transaction_ref');             
            $table->string('category');
            $table->longText('content');
            $table->enum('status', ['pending', 'processing', 'resolved', 'rejected', 'query', 'remark'])->default('pending');
            $table->text('comment')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->string('attachment')->nullable();
            $table->string('performed_by', 150)->nullable();
            $table->string('approved_by', 150)->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
