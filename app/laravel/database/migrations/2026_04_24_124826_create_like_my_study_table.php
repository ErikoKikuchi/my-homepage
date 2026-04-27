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
        Schema::create('like_my_study', function (Blueprint $table) {
            $table->foreignUuid('user_id')->constrained()-> cascadeOnDelete();
            $table->foreignUuid('my_study_id')->constrained()->cascadeOnDelete();
            $table->primary(['user_id', 'my_study_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('like_my_study');
    }
};
