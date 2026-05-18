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
        Schema::create('theme_my_study', function (Blueprint $table) {
            $table->foreignId('theme_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('my_study_id')->constrained('my_studies')->cascadeOnDelete();
            $table->primary(['my_study_id', 'theme_id']);
            $table->text('memo_text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_my_study');
    }
};
