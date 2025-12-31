<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('migration_forms', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('business_name');
            $table->string('business_address');
            $table->string('business_email');
            $table->string('state');
            $table->string('lga');
            $table->string('address');
            $table->string('nearest_bustop');
            $table->string('office_image')->nullable();
            $table->string('nepa_bill')->nullable();
            $table->string('cac_upload')->nullable();
            $table->enum('status', ['pending', 'processing', 'resolved', 'rejected', 'query'])->default('pending');
            $table->text('comment')->nullable();
            $table->string('performed_by', 150)->nullable();
            $table->string('approved_by', 150)->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('migration_forms');
    }
};