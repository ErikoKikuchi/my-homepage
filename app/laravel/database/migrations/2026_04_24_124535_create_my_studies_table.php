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
        Schema::create('my_studies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('room_id')->nullable()->constrained('rooms')->cascadeOnDelete();
            $table->enum('visibility', ['public','members','room_only','private'])->default('public');
            $table->string('title');
            $table->boolean('show_one_sentence_insight')->default(false);
            $table->string('one_sentence_insight')->nullable();
            $table->boolean('show_why_it_matters')->default(false);
            $table->text('why_it_matters')->nullable();
            $table->boolean('show_context')->default(false);
            $table->text('context')->nullable();
            $table->text('content');
            $table->boolean('show_my_experience')->default(false);
            $table->text('my_experience')->nullable();
            $table->enum('thinking_state', ['draft','in_progress','structured','provisional','completed'])->default('in_progress');
            $table->boolean('needs_revisit')->default(false);
            $table->boolean('is_practical')->default(false);
            $table->boolean('is_hot')->default(false);
            $table->boolean('show_friction_level')->default(false);
            $table->unsignedTinyInteger('friction_level')->default(3);
            $table->boolean('is_stock')->default(false);
            $table->boolean('is_incubating')->default(false);
            $table->boolean('allow_comments')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('my_studies');
    }
};
