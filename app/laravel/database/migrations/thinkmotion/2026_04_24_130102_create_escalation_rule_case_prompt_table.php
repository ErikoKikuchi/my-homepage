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
        Schema::create('escalation_rule_case_prompt', function (Blueprint $table) {
            $table->foreignId('escalation_rule_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('case_prompt_id')->constrained('case_prompts')->cascadeOnDelete();
            $table->primary(['escalation_rule_id', 'case_prompt_id']);
            $table->text('custom_text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('escalation_rule_case_prompt');
    }
};
