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
        Schema::connection('client_db')->create('guest_pass_usages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('guest_pass_purchase_id')->constrained()->restrictOnDelete();
            $table->foreignUuid('client_id')->constrained()->restrictOnDelete();
            $table->enum('pass_type', ['session', 'venue_addon']);
            $table->foreignUuid('pilates_session_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignUuid('reservation_id')->nullable()->constrained()->restrictOnDelete();
            $table->enum('reason', ['session_completed']);
            $table->uuid('active_reservation_id')->nullable()->storedAs('CASE WHEN voided_at IS NULL THEN reservation_id ELSE NULL END');
            $table->uuid('active_pilates_session_id')->nullable()->storedAs('CASE WHEN voided_at IS NULL THEN pilates_session_id ELSE NULL END');
            $table->unique(['active_reservation_id', 'pass_type'],'gpu_active_reservation_type_unique');
            $table->unique(['active_pilates_session_id', 'pass_type'],'gpu_active_session_type_unique');
            $table->text('note')->nullable();
            $table->uuid('recorded_by_admin_id')->nullable();
            $table->foreign('recorded_by_admin_id')->references('id')->on('thinkmotion_db.admins')->restrictOnDelete();
            $table->timestamp('used_at')->nullable();
            $table->timestamp('voided_at')->nullable();
            $table->uuid('voided_by_admin_id')->nullable()->index();
            $table->foreign('voided_by_admin_id')->references('id')->on('thinkmotion_db.admins')->restrictOnDelete();
            $table->text('void_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('client_db')->dropIfExists('guest_pass_usages');
    }
};
