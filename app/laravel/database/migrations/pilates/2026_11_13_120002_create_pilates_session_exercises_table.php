<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('client_db')->create('pilates_session_exercises', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pilates_session_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('exercise_id')->nullable()->constrained('pilates_exercises')->restrictOnDelete();
            $table->text('custom_name')->nullable();
            $table->text('comment')->nullable();
            $table->unsignedInteger('position');
            $table->timestamps();

            $table->unique(['pilates_session_id', 'position'], 'pilates_session_exercises_position_unique');
        });
    }

    public function down(): void
    {
        Schema::connection('client_db')->dropIfExists('pilates_session_exercises');
    }
};
