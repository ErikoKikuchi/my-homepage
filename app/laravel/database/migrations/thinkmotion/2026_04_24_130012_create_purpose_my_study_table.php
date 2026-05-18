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
        Schema::create('purpose_my_study', function (Blueprint $table) {
            $table->foreignId('purpose_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('my_study_id')->constrained('my_studies')->cascadeOnDelete();
            $table->primary(['purpose_id', 'my_study_id']);
            $table->text('custom_text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purpose_my_study');
    }
};
