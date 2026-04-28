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
        Schema::connection('client_db')->create('reservations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('lesson_slot_id')->constrained()->cascadeOnDelete();
            $table->uuid('user_id');
            $table->unsignedTinyInteger('participants')->default(1);
            $table->text('participants_name')->nullable();
            $table->text('note')->nullable();
            $table->enum('status', ['pending','waiting_venue','confirmed','canceled'])->default('pending');
            $table->timestamp('cancelled_at')->nullable();
            $table->enum('cancelled_reason', ['user','venue','instructor'])->nullable();
            $table->string('guest_name')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_line')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('client_db')->dropIfExists('reservations');
    }
};
