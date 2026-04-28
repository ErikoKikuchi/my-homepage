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
        Schema::create('memo_type_case_prompt', function (Blueprint $table) {
            $table->foreignId('memo_type_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('case_prompt_id')->constrained('case_prompts')->cascadeOnDelete();
            $table->primary(['memo_type_id', 'case_prompt_id']);
            $table->text('memo_text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memo_type_case_prompt');
    }
};
