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
        Schema::create('my_data_case_prompt', function (Blueprint $table) {
            $table->foreignUuid('my_data_id')->constrained('my_data')->cascadeOnDelete();
            $table->foreignUuid('case_prompt_id')->constrained('case_prompts')->cascadeOnDelete();
            $table->primary(['case_prompt_id', 'my_data_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('my_data_case_prompt');
    }
};
