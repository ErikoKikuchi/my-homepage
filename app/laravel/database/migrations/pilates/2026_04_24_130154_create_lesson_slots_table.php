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
            $table->foreignUuid('lesson_template_id')->constrained();
            $table->date('date');
            $table->foreignUuid('location_id')->nullable()->constrained();
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
