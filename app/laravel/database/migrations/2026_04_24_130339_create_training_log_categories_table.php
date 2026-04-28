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
        Schema::connection('client_db')->create('training_log_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('training_log_id')->constrained()->cascadeOnDelete();
            $table->enum('category', ['stretch','care','strength','endurance'])->default('stretch');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('client_db')->dropIfExists('training_log_categories');
    }
};
