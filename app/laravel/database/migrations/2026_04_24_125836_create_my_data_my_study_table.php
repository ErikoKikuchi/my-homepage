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
        Schema::create('my_data_my_study', function (Blueprint $table) {
            $table->foreignUuid('my_data_id')->constrained('my_data')->cascadeOnDelete();
            $table->foreignUuid('my_study_id')->constrained('my_studies')->cascadeOnDelete();
            $table->primary(['my_data_id', 'my_study_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('my_data_my_study');
    }
};
