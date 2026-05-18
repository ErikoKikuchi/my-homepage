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
        Schema::create('case_prompts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('room_id')->nullable()->constrained('rooms')->cascadeOnDelete();
            $table->enum('visibility', ['public','members','room_only','private'])->default('public');
            $table->string('title');
            $table->text('prompt_text')->nullable();
            $table->text('constraint_text')->nullable();
            $table->unsignedTinyInteger('priority')->default(3);
            $table->unsignedTinyInteger('confidence')->default(3);
            $table->unsignedTinyInteger('risk_level')->default(0);
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
        Schema::dropIfExists('case_prompts');
    }
};
