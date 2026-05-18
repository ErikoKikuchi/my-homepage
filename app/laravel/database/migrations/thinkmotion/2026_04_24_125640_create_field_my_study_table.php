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
        Schema::create('field_my_study', function (Blueprint $table) {
            $table->foreignId('field_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('my_study_id')->constrained('my_studies')->cascadeOnDelete();
            $table->primary(['field_id', 'my_study_id']);
            $table->enum('staging', ['acute','recovery', 'maintenance', 'outpatient','other'])->default('other');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('field_my_study');
    }
};
