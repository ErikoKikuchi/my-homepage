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
        Schema::create('my_reference_case_prompt', function (Blueprint $table) {
            $table->foreignUuid('my_reference_id')->constrained('my_references')->cascadeOnDelete();
            $table->foreignUuid('case_prompt_id')->constrained('case_prompts')->cascadeOnDelete();
            $table->primary(['my_reference_id', 'case_prompt_id']);
            $table->text('reference_memo')->nullable();
            $table->string('chapter')->nullable();
            $table->string('page')->nullable();
            $table->string('data_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('my_reference_case_prompt');
    }
};
