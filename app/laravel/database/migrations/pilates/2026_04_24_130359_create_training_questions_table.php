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
        Schema::connection('client_db')->create('training_questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('training_log_id')->constrained()->cascadeOnDelete();
            $table->text('question');
            $table->enum('urgency', ['urgent','next_session'])->default('next_session');
            $table->text('answer');
            $table->dateTime('answered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('client_db')->dropIfExists('training_questions');
    }
};
