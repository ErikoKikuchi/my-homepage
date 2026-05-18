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
        Schema::create('room_case_prompt', function (Blueprint $table) {
            $table->foreignUuid('room_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('case_prompt_id')->constrained('case_prompts')->cascadeOnDelete();
            $table->primary(['room_id', 'case_prompt_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_case_prompt');
    }
};
