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
        Schema::connection('client_db')->create('lesson_slots', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->unsignedTinyInteger('capacity');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('client_db')->dropIfExists('lesson_slots');
    }
};
