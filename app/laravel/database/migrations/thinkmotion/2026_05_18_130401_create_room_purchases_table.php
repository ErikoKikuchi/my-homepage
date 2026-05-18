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
        Schema::create('room_purchases', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('room_id')->references('id')->on('rooms')->restrictOnDelete();
            $table->foreignUuid('admin')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamp('purchased_at');
            $table->timestamp('expires_at');
            $table->tinyInteger('duration_months')->default(6);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_purchases');
    }
};
