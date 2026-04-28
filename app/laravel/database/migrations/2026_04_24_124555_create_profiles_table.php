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
        Schema::create('profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('experience_year_id')->nullable()->constrained('experience_years');
            $table->foreignId('specialty_type_id')->nullable()->constrained('specialty_types');
            $table->string('display_name');
            $table->string('occupation')->nullable();
            $table->string('bio')->nullable();
            $table->string('icon')->nullable();
            $table->json('links')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
