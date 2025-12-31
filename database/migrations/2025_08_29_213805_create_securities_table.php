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
        Schema::create('securities', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('transaction_pin')->nullable();
        $table->string('otp')->nullable();
        $table->timestamp('otp_expires_at')->nullable();
        $table->string('security_question_1')->nullable();
        $table->string('security_answer_1')->nullable();
        $table->string('security_question_2')->nullable();
        $table->string('security_answer_2')->nullable();
        $table->string('security_question_3')->nullable();
        $table->string('security_answer_3')->nullable();
        $table->string('security_question_4')->nullable();
        $table->string('security_answer_4')->nullable();
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
        Schema::dropIfExists('securities');
    }
};
