<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('available_banks', function (Blueprint $table) {
            $table->id(); // AUTO_INCREMENT PRIMARY KEY
            $table->string('bank_name', 100);
            $table->string('bank_code', 10);
            $table->timestamps(); // Adds created_at and updated_at with proper defaults
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('available_banks');
    }
};
