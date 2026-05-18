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
        Schema::create('case_prompt_comments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()-> cascadeOnDelete();
            $table->foreignUuid('case_prompt_id')->constrained()->cascadeOnDelete();
            $table->text('comment');
            $table->enum('comment_type',['question', 'supplement', 'different_view', 'experience', 'answer']);
            $table->boolean('is_private')->default(false);
            $table->boolean('accepted_by_author')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_prompt_comments');
    }
};
