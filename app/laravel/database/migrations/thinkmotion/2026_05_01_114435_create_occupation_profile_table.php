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
        Schema::create('occupation_profile', function (Blueprint $table) {
            $table->foreignUuid('profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('occupation_id')->constrained()->restrictOnDelete();
            $table->primary(['profile_id', 'occupation_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('occupation_profile');
    }
};
